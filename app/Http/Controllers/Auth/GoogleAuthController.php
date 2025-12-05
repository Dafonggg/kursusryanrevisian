<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        try {
            // Check if Google OAuth is configured
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi. Silakan hubungi administrator atau cek file .env');
            }

            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal mengarahkan ke Google. Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Check if Google OAuth is configured
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi. Silakan cek file .env');
            }

            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser || !$googleUser->email) {
                return redirect()->route('login')->with('error', 'Gagal mendapatkan informasi dari Google. Silakan coba lagi.');
            }

            // Check if user exists with this email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                
                // Update email_verified_at if not set
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name ?? $googleUser->email,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)), // Random password for Google-only users
                    'role' => 'student', // Default role
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            // Handle Google avatar - hanya jika user belum punya avatar
            $this->handleGoogleAvatar($user, $googleUser);

            // Check if this is a refresh avatar request
            $isRefreshAvatar = session('refresh_avatar', false);
            if ($isRefreshAvatar) {
                session()->forget('refresh_avatar');
                // Redirect back to profile page
                if ($user->role == 'admin') {
                    return redirect()->route('admin.profile')->with('success', 'Avatar berhasil diperbarui dari Google!');
                } elseif ($user->role == 'instructor') {
                    return redirect()->route('instructor.profile')->with('success', 'Avatar berhasil diperbarui dari Google!');
                } elseif ($user->role == 'user' || $user->role == 'student') {
                    return redirect()->route('student.profile')->with('success', 'Avatar berhasil diperbarui dari Google!');
                }
            }

            // Login the user
            Auth::login($user, true);

            // Redirect based on role
            if ($user->role == 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role == 'instructor') {
                return redirect()->intended(route('instructor.dashboard'));
            } elseif ($user->role == 'user' || $user->role == 'student') {
                return redirect()->intended(route('home'));
            }

            return redirect()->route('home');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error('Google OAuth Connection Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Tidak dapat terhubung ke Google. Pastikan koneksi internet Anda aktif.');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth Invalid State: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Session expired. Silakan coba lagi.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Show detailed error in development, generic in production
            $errorMessage = app()->environment('local') 
                ? 'Error: ' . $e->getMessage() 
                : 'Gagal autentikasi dengan Google. Pastikan Google OAuth sudah dikonfigurasi dengan benar.';
                
            return redirect()->route('login')->with('error', $errorMessage);
        }
    }

    /**
     * Refresh avatar from Google (redirect to Google OAuth)
     */
    public function refreshAvatar()
    {
        try {
            // Check if Google OAuth is configured
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->back()->with('error', 'Google OAuth belum dikonfigurasi.');
            }

            // Store intended URL to redirect back after OAuth
            session(['refresh_avatar' => true]);

            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google Avatar Refresh Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengarahkan ke Google. Silakan coba lagi.');
        }
    }

    /**
     * Handle Google avatar download and save
     */
    private function handleGoogleAvatar(User $user, $googleUser)
    {
        try {
            // Get avatar URL from Google user
            $avatarUrl = $googleUser->avatar ?? $googleUser->getAvatar() ?? null;
            
            if (!$avatarUrl) {
                return; // No avatar from Google
            }

            // Get or create user profile
            $profile = $user->profile;
            if (!$profile) {
                $profile = new UserProfile(['user_id' => $user->id]);
            }

            // Save avatar URL from Google (always update to get latest avatar URL)
            $profile->avatar_url = $avatarUrl;

            // Only download avatar if user doesn't have one yet
            // This allows users to change their avatar later
            if (!$profile->photo_path) {
                // Download avatar from Google
                $response = Http::timeout(10)->get($avatarUrl);
                
                if ($response->successful()) {
                    // Get file extension from URL or default to jpg
                    $extension = 'jpg';
                    $urlParts = parse_url($avatarUrl);
                    if (isset($urlParts['path'])) {
                        $pathInfo = pathinfo($urlParts['path']);
                        if (isset($pathInfo['extension']) && in_array(strtolower($pathInfo['extension']), ['jpg', 'jpeg', 'png', 'gif'])) {
                            $extension = strtolower($pathInfo['extension']);
                        }
                    }

                    // Generate unique filename
                    $filename = 'profiles/' . $user->id . '_' . Str::random(10) . '.' . $extension;
                    
                    // Save to storage
                    Storage::disk('public')->put($filename, $response->body());
                    
                    // Update profile
                    $profile->photo_path = $filename;
                }
            }
            
            // Save profile with avatar_url
            $profile->save();
        } catch (\Exception $e) {
            // Log error but don't break the login process
            \Log::warning('Failed to download Google avatar for user ' . $user->id . ': ' . $e->getMessage());
        }
    }
}


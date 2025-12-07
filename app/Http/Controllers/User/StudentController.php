<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\MaterialProgress;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Payment;
use App\Models\Certificate;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\EnrollmentStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ringkasan Dashboard
        $summary = $this->getDashboardSummary($user);
        
        // Data real untuk Continue Learning
        $continue_learning = $this->getContinueLearning($user);
        
        // Data real untuk Active Days Counter
        $active_days = $this->getActiveDays($user);
        
        // Data real untuk Payment Status
        $payment_status = $this->getPaymentStatus($user);
        
        // Data real untuk Certificate Ready
        $ready_certificates = $this->getReadyCertificates($user);
        $ready_certificates_count = count($ready_certificates);
        
        // Data real untuk Chat Shortcut
        $chat_shortcut = $this->getChatShortcut($user);
        $admin_shortcut = $this->getAdminShortcut();

        return view('student.dashboard.index', compact(
            'summary',
            'continue_learning',
            'active_days',
            'payment_status',
            'ready_certificates',
            'ready_certificates_count',
            'chat_shortcut',
            'admin_shortcut'
        ));
    }

    public function myCourses()
    {
        $user = Auth::user();
        
        // Ambil semua enrollment dengan status aktif dan selesai
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.materials', 'payments' => function($query) {
                $query->latest('created_at')->limit(1);
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Pisahkan aktif dan selesai
        $activeEnrollments = $enrollments->filter(function($enrollment) {
            return $enrollment->status === EnrollmentStatus::Active && 
                   ($enrollment->expires_at === null || $enrollment->expires_at->isFuture());
        });
        
        $completedEnrollments = $enrollments->filter(function($enrollment) {
            return $enrollment->status === EnrollmentStatus::Completed || 
                   ($enrollment->expires_at !== null && $enrollment->expires_at->isPast());
        });

        return view('student.courses.index', compact(
            'activeEnrollments',
            'completedEnrollments'
        ));
    }


    public function payment(Request $request)
    {
        $user = Auth::user();
        
        // Get payment by ID if provided
        $payment = null;
        if ($request->has('payment')) {
            $payment = Payment::where('id', $request->payment)
                ->whereHas('enrollment', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first();
        }
        
        // Data real untuk Payment Status
        $payment_status = $this->getPaymentStatus($user);
        
        // Get all pending payments for this user
        $pendingPayments = Payment::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', PaymentStatus::Pending)
            ->with(['enrollment.course'])
            ->latest('created_at')
            ->get();

        return view('student.payment.index', compact(
            'payment_status',
            'pendingPayments',
            'payment'
        ));
    }
    
    /**
     * Upload bukti pembayaran
     */
    public function uploadPaymentProof(Request $request, Payment $payment)
    {
        // Verify payment belongs to user
        $user = Auth::user();
        $payment->load('enrollment');
        
        if ($payment->enrollment->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan.');
        }
        
        if ($payment->status !== PaymentStatus::Pending) {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses.');
        }
        
        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            'bank' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:255',
        ]);
        
        // Upload bukti pembayaran
        $proofPath = $request->file('proof')->store('payment-proofs', 'public');
        
        // Update payment meta
        $meta = $payment->meta ?? [];
        $meta['proof'] = $proofPath;
        $meta['proof_image'] = $proofPath; // Alias
        $meta['bukti'] = $proofPath; // Alias
        
        if ($request->filled('bank')) {
            $meta['bank'] = $request->bank;
            $meta['bank_name'] = $request->bank;
        }
        
        if ($request->filled('account_number')) {
            $meta['account_number'] = $request->account_number;
            $meta['no_rekening'] = $request->account_number;
        }
        
        if ($request->filled('reference')) {
            $payment->reference = $request->reference;
        }
        
        $payment->meta = $meta;
        $payment->save();
        
        return redirect()->route('student.payment', ['payment' => $payment->id])
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function certificate()
    {
        $user = Auth::user();
        
        // Data real untuk Certificate Ready
        $ready_certificates = $this->getReadyCertificates($user);
        $ready_certificates_count = count($ready_certificates);

        return view('student.dashboard.index', compact(
            'ready_certificates',
            'ready_certificates_count'
        ))->with('showCertificateOnly', true);
    }

    /**
     * Download certificate as PDF
     */
    public function downloadCertificate(Certificate $certificate)
    {
        $user = Auth::user();
        
        // Verify certificate belongs to user
        if ($certificate->enrollment->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Check if file exists
        if (!$certificate->file_path || !Storage::exists($certificate->file_path)) {
            return redirect()->route('student.certificate')
                ->with('error', 'File sertifikat tidak tersedia.');
        }
        
        return Storage::download($certificate->file_path, 'sertifikat-' . $certificate->certificate_no . '.pdf');
    }

    public function chat()
    {
        $user = Auth::user();
        
        // Ambil semua conversation yang melibatkan user ini
        $conversations = Conversation::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['participants', 'latestMessage.user'])
            ->withCount('messages')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        // Ambil admin dan instruktur untuk memulai chat baru
        $admins = User::where('role', 'admin')->get();
        $instructors = User::where('role', 'instructor')->get();

        return view('student.chat.index', compact(
            'conversations',
            'admins',
            'instructors'
        ));
    }
    
    /**
     * Menampilkan detail conversation
     */
    public function showChat($conversationId)
    {
        $user = Auth::user();
        
        $conversation = Conversation::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['participants', 'messages.user'])
            ->findOrFail($conversationId);
        
        // Mark messages as read (jika ada fitur read status)
        
        return view('student.chat.show', compact('conversation'));
    }
    
    /**
     * Membuat conversation baru atau mendapatkan yang sudah ada
     */
    public function createOrGetConversation(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $otherUser = User::findOrFail($request->user_id);
        
        // Cek apakah sudah ada conversation
        $conversation = Conversation::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();
        
        if (!$conversation) {
            $conversation = Conversation::create([
                'title' => 'Chat dengan ' . $otherUser->name,
            ]);
            $conversation->participants()->attach([$user->id, $otherUser->id]);
        }
        
        return redirect()->route('student.chat.show', $conversation->id);
    }
    
    /**
     * Mengirim pesan
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $user = Auth::user();
        
        // Verify user is participant
        $conversation = Conversation::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($conversationId);
        
        $request->validate([
            'body' => 'required|string|max:5000',
        ]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $request->body,
        ]);
        
        $conversation->touch(); // Update updated_at
        
        return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Menampilkan materi kursus yang diikuti student
     */
    public function materials(Request $request, $courseSlug = null)
    {
        $user = Auth::user();
        
        // Jika ada courseSlug, tampilkan materi untuk kursus tertentu
        if ($courseSlug) {
            $course = Course::where('slug', $courseSlug)->firstOrFail();
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
                ->firstOrFail();
            
            $materials = CourseMaterial::where('course_id', $course->id)
                ->orderBy('order')
                ->get();
            
            // Ambil progress untuk setiap materi
            $progresses = MaterialProgress::where('enrollment_id', $enrollment->id)
                ->whereIn('material_id', $materials->pluck('id'))
                ->get()
                ->keyBy('material_id');
            
            // Tentukan status setiap materi (locked, available, completed)
            $materialsWithStatus = $materials->map(function ($material) use ($materials, $progresses, $enrollment) {
                $progress = $progresses->get($material->id);
                $isCompleted = $progress && $progress->completed_at !== null;
                
                // Cek apakah materi sebelumnya sudah selesai
                $previousMaterial = $materials->where('order', '<', $material->order)
                    ->sortByDesc('order')
                    ->first();
                
                $isLocked = false;
                if ($previousMaterial) {
                    $previousProgress = $progresses->get($previousMaterial->id);
                    $isLocked = !$previousProgress || $previousProgress->completed_at === null;
                }
                
                return [
                    'material' => $material,
                    'status' => $isCompleted ? 'completed' : ($isLocked ? 'locked' : 'available'),
                    'progress' => $progress,
                ];
            });
            
            // Hitung progress percentage
            $completedCount = $materialsWithStatus->where('status', 'completed')->count();
            $totalCount = $materials->count();
            $progressPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
            
            return view('student.materials.index', compact('course', 'materials', 'enrollment', 'materialsWithStatus', 'progressPercentage'));
        }
        
        // Jika tidak ada courseSlug, tampilkan semua kursus yang diikuti
        $enrollments = Enrollment::where('user_id', $user->id)
            ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
            ->with(['course.materials' => function($query) {
                $query->orderBy('order');
            }])
            ->get();
        
        return view('student.materials.courses', compact('enrollments'));
    }

    /**
     * Menampilkan detail materi
     */
    public function showMaterial($courseSlug, $materialId)
    {
        $user = Auth::user();
        
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
            ->firstOrFail();
        
        $material = CourseMaterial::where('id', $materialId)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        // Cek apakah materi sebelumnya sudah selesai
        $previousMaterial = CourseMaterial::where('course_id', $course->id)
            ->where('order', '<', $material->order)
            ->orderByDesc('order')
            ->first();
        
        $canAccess = true;
        if ($previousMaterial) {
            $previousProgress = MaterialProgress::where('enrollment_id', $enrollment->id)
                ->where('material_id', $previousMaterial->id)
                ->whereNotNull('completed_at')
                ->first();
            $canAccess = $previousProgress !== null;
        }
        
        if (!$canAccess) {
            return redirect()->route('student.materials', $courseSlug)
                ->with('error', 'Anda harus menyelesaikan materi sebelumnya terlebih dahulu.');
        }
        
        // Cek apakah materi sudah completed
        $progress = MaterialProgress::where('enrollment_id', $enrollment->id)
            ->where('material_id', $material->id)
            ->first();
        
        $isCompleted = $progress && $progress->completed_at !== null;
        
        // Ambil materi berikutnya untuk navigasi
        $nextMaterial = CourseMaterial::where('course_id', $course->id)
            ->where('order', '>', $material->order)
            ->orderBy('order')
            ->first();
        
        return view('student.materials.show', compact('course', 'material', 'enrollment', 'isCompleted', 'nextMaterial'));
    }

    /**
     * Mark materi sebagai selesai
     */
    public function completeMaterial(Request $request, $courseSlug, $materialId)
    {
        $user = Auth::user();
        
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
            ->firstOrFail();
        
        $material = CourseMaterial::where('id', $materialId)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        // Cek apakah materi sebelumnya sudah selesai
        $previousMaterial = CourseMaterial::where('course_id', $course->id)
            ->where('order', '<', $material->order)
            ->orderByDesc('order')
            ->first();
        
        if ($previousMaterial) {
            $previousProgress = MaterialProgress::where('enrollment_id', $enrollment->id)
                ->where('material_id', $previousMaterial->id)
                ->whereNotNull('completed_at')
                ->first();
            
            if (!$previousProgress) {
                return redirect()->back()
                    ->with('error', 'Anda harus menyelesaikan materi sebelumnya terlebih dahulu.');
            }
        }
        
        // Create or update progress
        $progress = MaterialProgress::firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'material_id' => $material->id,
            ],
            [
                'completed_at' => now(),
            ]
        );
        
        // Jika sudah ada tapi belum completed, update
        if (!$progress->completed_at) {
            $progress->update(['completed_at' => now()]);
        }
        
        // Cek apakah semua materi sudah selesai, jika ya update enrollment status
        $enrollment->checkAndMarkCompleted();
        
        // Ambil materi berikutnya
        $nextMaterial = CourseMaterial::where('course_id', $course->id)
            ->where('order', '>', $material->order)
            ->orderBy('order')
            ->first();
        
        if ($nextMaterial) {
            return redirect()->route('student.materials.show', [$courseSlug, $nextMaterial->id])
                ->with('success', 'Materi berhasil diselesaikan! Lanjut ke materi berikutnya.');
        }
        
        return redirect()->route('student.materials', $courseSlug)
            ->with('success', 'Selamat! Anda telah menyelesaikan semua materi kursus ini.');
    }


    /**
     * Helper: Get Continue Learning Data
     */
    private function getContinueLearning($user)
    {
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->with(['course.materials' => function($query) {
                $query->orderBy('order');
            }])
            ->latest('started_at')
            ->first();
        
        if ($activeEnrollment && $activeEnrollment->course) {
            $course = $activeEnrollment->course;
            $materials = $course->materials->sortBy('order');
            $totalMaterials = $materials->count();
            
            if ($totalMaterials === 0) {
                return null;
            }
            
            // Ambil progress untuk semua materi
            $progresses = MaterialProgress::where('enrollment_id', $activeEnrollment->id)
                ->whereIn('material_id', $materials->pluck('id'))
                ->whereNotNull('completed_at')
                ->get()
                ->keyBy('material_id');
            
            // Cari materi pertama yang belum selesai
            $nextMaterial = null;
            foreach ($materials as $material) {
                if (!isset($progresses[$material->id])) {
                    $nextMaterial = $material;
                    break;
                }
            }
            
            // Jika semua sudah selesai, ambil materi terakhir
            if (!$nextMaterial) {
                $nextMaterial = $materials->last();
            }
            
            // Hitung progress percentage
            $completedCount = $progresses->count();
            $progressPercentage = $totalMaterials > 0 ? round(($completedCount / $totalMaterials) * 100) : 0;
            
            if ($nextMaterial) {
                return (object)[
                    'course_id' => $course->id,
                    'course_slug' => $course->slug,
                    'course_name' => $course->title,
                    'course_image' => $course->image ? Storage::url($course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                    'lesson_name' => $nextMaterial->title,
                    'lesson_id' => $nextMaterial->id,
                    'progress_percentage' => $progressPercentage,
                ];
            }
        }
        
        return null;
    }


    /**
     * Helper: Get Active Days Data
     */
    private function getActiveDays($user)
    {
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->latest('started_at')
            ->first();
        
        if ($activeEnrollment && $activeEnrollment->expires_at) {
            // Hitung selisih hari (hanya tanggal, tanpa jam)
            $remainingDays = max(0, now()->startOfDay()->diffInDays($activeEnrollment->expires_at->startOfDay()));
            $totalDays = $activeEnrollment->started_at && $activeEnrollment->expires_at 
                ? $activeEnrollment->started_at->startOfDay()->diffInDays($activeEnrollment->expires_at->startOfDay()) 
                : 90;
            $usedDays = $activeEnrollment->started_at 
                ? max(0, now()->startOfDay()->diffInDays($activeEnrollment->started_at->startOfDay())) 
                : 0;
            $activeDaysPercentage = $totalDays > 0 ? min(100, (int)(($usedDays / $totalDays) * 100)) : 0;
            
            return (object)[
                'remaining_days' => $remainingDays,
                'active_days_percentage' => $activeDaysPercentage,
                'enrollment_date' => $activeEnrollment->started_at ? $activeEnrollment->started_at->format('d M Y') : '-',
                'expiry_date' => $activeEnrollment->expires_at->format('d M Y'),
            ];
        }
        
        return null;
    }

    /**
     * Helper: Get Payment Status Data
     */
    private function getPaymentStatus($user)
    {
        $lastPayment = Payment::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->first();
        
        if ($lastPayment) {
            $statusBadge = match($lastPayment->status->value) {
                'paid' => 'success',
                'pending' => 'warning',
                'failed' => 'danger',
                'refunded' => 'info',
                default => 'secondary'
            };
            
            return (object)[
                'payment_id' => $lastPayment->id,
                'payment_amount' => 'Rp ' . number_format($lastPayment->amount, 0, ',', '.'),
                'payment_status' => ucfirst($lastPayment->status->value),
                'payment_status_badge' => $statusBadge,
                'payment_date' => $lastPayment->paid_at ? $lastPayment->paid_at->format('d M Y') : $lastPayment->created_at->format('d M Y'),
                'payment_method' => ucfirst($lastPayment->method->value ?? 'Transfer'),
                'invoice_url' => route('student.payment') . '?payment=' . $lastPayment->id,
            ];
        }
        
        return null;
    }

    /**
     * Helper: Get Ready Certificates Data
     */
    private function getReadyCertificates($user)
    {
        $certificates = Certificate::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['enrollment.course'])
            ->latest('issued_at')
            ->get();
        
        $ready_certificates = [];
        foreach ($certificates as $cert) {
            $course = $cert->enrollment->course;
            $ready_certificates[] = (object)[
                'id' => $cert->id,
                'course_name' => $course->title,
                'course_category' => 'Kursus',
                'course_image' => $course->image ? Storage::url($course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                'certificate_date' => $cert->issued_at ? $cert->issued_at->format('d M Y') : '-',
                'certificate_url' => $cert->file_path ? route('student.certificate.download', $cert->id) : '#',
            ];
        }
        
        return $ready_certificates;
    }

    /**
     * Helper: Get Dashboard Summary
     */
    private function getDashboardSummary($user)
    {
        // Jumlah kursus aktif
        $activeCoursesCount = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->count();
        
        // Masa berlaku (dari enrollment terbaru)
        $latestEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->latest('started_at')
            ->first();
        
        $expiryDate = null;
        $remainingDays = null;
        if ($latestEnrollment && $latestEnrollment->expires_at) {
            $expiryDate = $latestEnrollment->expires_at->format('d M Y');
            // Hitung selisih hari (hanya tanggal, tanpa jam)
            $remainingDays = max(0, now()->startOfDay()->diffInDays($latestEnrollment->expires_at->startOfDay()));
        }
        
        // Status pembayaran terakhir
        $lastPayment = Payment::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->first();
        
        $paymentStatus = 'Tidak ada';
        $paymentStatusBadge = 'secondary';
        if ($lastPayment) {
            $paymentStatus = ucfirst($lastPayment->status->value);
            $paymentStatusBadge = match($lastPayment->status->value) {
                'paid' => 'success',
                'pending' => 'warning',
                'failed' => 'danger',
                'refunded' => 'info',
                default => 'secondary'
            };
        }
        
        return (object)[
            'active_courses_count' => $activeCoursesCount,
            'expiry_date' => $expiryDate,
            'remaining_days' => $remainingDays,
            'payment_status' => $paymentStatus,
            'payment_status_badge' => $paymentStatusBadge,
        ];
    }

    /**
     * Helper: Get Chat Shortcut Data
     */
    private function getChatShortcut($user)
    {
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->with('course')
            ->latest('started_at')
            ->first();
        
        if ($activeEnrollment && $activeEnrollment->course && $activeEnrollment->course->owner_id) {
            $instructor = User::with('profile')->find($activeEnrollment->course->owner_id);
            if ($instructor) {
                $profile = $instructor->profile;
                $avatar = $profile 
                    ? $profile->getAvatarUrl()
                    : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
                
                return (object)[
                    'instructor_id' => $instructor->id,
                    'instructor_name' => $instructor->name,
                    'instructor_avatar' => $avatar,
                ];
            }
        }
        
        return null;
    }

    /**
     * Helper: Get Admin Shortcut Data
     */
    private function getAdminShortcut()
    {
        $admin = User::where('role', 'admin')
            ->with('profile')
            ->first();
        
        if ($admin) {
            $profile = $admin->profile;
            $avatar = $profile 
                ? $profile->getAvatarUrl()
                : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
            
            return (object)[
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_avatar' => $avatar,
            ];
        }
        
        return null;
    }

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        return view('student.profile.index', compact('user', 'profile'));
    }

    /**
     * Update profil student
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kk' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);
        
        // Update user name
        $user->update([
            'name' => $request->name,
        ]);
        
        // Update atau create profile
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        
        $profile->phone = $request->phone;
        $profile->address = $request->address;
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($profile->photo_path) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $profile->photo_path = $request->file('photo')->store('profiles', 'public');
        }
        
        // Handle KTP upload
        if ($request->hasFile('ktp')) {
            if ($profile->ktp_path) {
                Storage::disk('public')->delete($profile->ktp_path);
            }
            $profile->ktp_path = $request->file('ktp')->store('documents', 'public');
        }
        
        // Handle KK upload
        if ($request->hasFile('kk')) {
            if ($profile->kk_path) {
                Storage::disk('public')->delete($profile->kk_path);
            }
            $profile->kk_path = $request->file('kk')->store('documents', 'public');
        }
        
        $profile->save();
        
        return redirect()->route('student.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}

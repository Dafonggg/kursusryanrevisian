<?php

namespace App\Http\Controllers\Instuctor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Certificate;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use App\Models\UserProfile;
use App\Enums\EnrollmentStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function index()
    {
        $instructorId = Auth::id();
        
        // My Courses - kursus yang diajar oleh instruktur ini
        $my_courses = $this->getMyCourses();
        $my_courses_count = count($my_courses);

        // Latest Messages
        $latest_messages = $this->getLatestMessages();
        $unread_messages_count = $this->getUnreadMessagesCount();

        return view('instructor.dashboard.index', compact(
            'my_courses',
            'my_courses_count',
            'latest_messages',
            'unread_messages_count'
        ));
    }

    public function courses()
    {
        // Data untuk halaman Kursus Saya (berisi my-courses)
        $my_courses = $this->getMyCourses();
        $my_courses_count = count($my_courses);

        return view('instructor.dashboard.courses', compact(
            'my_courses',
            'my_courses_count'
        ));
    }

    public function messages()
    {
        // Data untuk halaman Chat (berisi latest-messages)
        $latest_messages = $this->getLatestMessages();
        $unread_messages_count = $this->getUnreadMessagesCount();

        return view('instructor.dashboard.messages', compact(
            'latest_messages',
            'unread_messages_count'
        ));
    }

    /**
     * Menampilkan detail conversation
     */
    public function showChat($conversationId)
    {
        $instructorId = Auth::id();
        
        $conversation = Conversation::whereHas('participants', function($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            })
            ->with(['participants', 'messages.user'])
            ->findOrFail($conversationId);
        
        return view('instructor.dashboard.chat-show', compact('conversation'));
    }

    /**
     * Membuat conversation baru atau mendapatkan yang sudah ada
     */
    public function createOrGetConversation(Request $request)
    {
        $instructorId = Auth::id();
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $otherUser = User::findOrFail($request->user_id);
        
        // Cek apakah sudah ada conversation
        $conversation = Conversation::whereHas('participants', function($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            })
            ->whereHas('participants', function($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();
        
        if (!$conversation) {
            $conversation = Conversation::create([
                'title' => 'Chat dengan ' . $otherUser->name,
            ]);
            $conversation->participants()->attach([$instructorId, $otherUser->id]);
        }
        
        return redirect()->route('instructor.chat.show', $conversation->id);
    }

    /**
     * Mengirim pesan
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $instructorId = Auth::id();
        
        // Verify instructor is participant
        $conversation = Conversation::whereHas('participants', function($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            })
            ->findOrFail($conversationId);
        
        $request->validate([
            'body' => 'required|string|max:5000',
        ]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $instructorId,
            'body' => $request->body,
        ]);
        
        return redirect()->route('instructor.chat.show', $conversation->id)
            ->with('success', 'Pesan berhasil dikirim!');
    }

    // Helper methods untuk mendapatkan data dari database
    private function getMyCourses()
    {
        $instructorId = Auth::id();
        
        // Ambil kursus yang ditugaskan kepada instruktur ini (instructor_id = instructorId)
        $courses = Course::where('instructor_id', $instructorId)
        ->withCount([
            'enrollments' => function ($query) {
                $query->where('status', EnrollmentStatus::Active);
            },
            'materials'
        ])
        ->get();
        
        return $courses->map(function ($course) {
            return (object)[
                'course_id' => $course->id,
                'course_slug' => $course->slug,
                'course_name' => $course->title,
                'course_category' => 'Course',
                'course_image' => $course->image ? asset('storage/' . $course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                'active_participants' => $course->enrollments_count ?? 0,
                'total_sessions' => $course->materials_count ?? 0,
            ];
        })->toArray();
    }

    private function getLatestMessages()
    {
        $instructorId = Auth::id();
        
        // Ambil conversation yang melibatkan instruktur ini dengan pesan terbaru
        $conversations = Conversation::whereHas('participants', function ($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })
        ->with(['latestMessage.user', 'participants'])
        ->whereHas('messages')
        ->orderBy('updated_at', 'desc')
        ->take(10)
        ->get();
        
        return $conversations->map(function ($conversation) use ($instructorId) {
            $latestMessage = $conversation->latestMessage;
            $otherParticipant = $conversation->participants->where('id', '!=', $instructorId)->first();
            
            if (!$latestMessage) {
                return null;
            }
            
            $createdAt = Carbon::parse($latestMessage->created_at);
            
            return (object)[
                'message_id' => $latestMessage->id,
                'conversation_id' => $conversation->id,
                'sender_name' => $latestMessage->user->name ?? 'N/A',
                'sender_avatar' => $latestMessage->user->profile && $latestMessage->user->profile->photo_path 
                    ? asset('storage/' . $latestMessage->user->profile->photo_path)
                    : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-1.jpg'),
                'other_participant_name' => $otherParticipant->name ?? 'N/A',
                'conversation_title' => $conversation->title ?? 'Chat',
                'message_preview' => substr($latestMessage->body ?? '', 0, 50) . (strlen($latestMessage->body ?? '') > 50 ? '...' : ''),
                'message_subject' => 'Pesan',
                'message_date' => $createdAt->format('d M Y'),
                'message_time' => $createdAt->format('H:i'),
            ];
        })->filter()->toArray();
    }

    private function getUnreadMessagesCount()
    {
        $instructorId = Auth::id();
        
        // Hitung pesan yang bukan dari instruktur ini (pesan masuk)
        return Message::whereHas('conversation', function ($query) use ($instructorId) {
            $query->whereHas('participants', function ($q) use ($instructorId) {
                $q->where('user_id', $instructorId);
            });
        })
        ->where('user_id', '!=', $instructorId)
        ->count();
    }

    /**
     * Menampilkan halaman Quick Actions
     */
    public function quickActions()
    {
        return view('instructor.quick-actions');
    }

    // ==================== PESERTA KURSUS METHODS ====================
    
    /**
     * Menampilkan daftar peserta untuk kursus tertentu
     */
    public function students($courseSlug)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->where('instructor_id', $instructorId)
            ->firstOrFail();
        
        $enrollments = Enrollment::where('course_id', $course->id)
            ->with(['user', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('instructor.dashboard.students', compact('course', 'enrollments'));
    }

    // ==================== RIWAYAT TRANSAKSI METHODS ====================
    
    /**
     * Menampilkan riwayat transaksi untuk kursus instruktur
     */
    public function transactions()
    {
        $instructorId = Auth::id();
        
        // Ambil semua kursus yang ditugaskan kepada instruktur ini
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');
        
        // Ambil semua payment dari enrollment kursus-kursus tersebut
        $payments = Payment::whereHas('enrollment', function ($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds);
        })
        ->with(['enrollment.course', 'enrollment.user'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('instructor.dashboard.transactions', compact('payments'));
    }

    // ==================== SERTIFIKAT METHODS ====================
    
    /**
     * Menampilkan daftar sertifikat untuk kursus instruktur
     */
    public function certificates()
    {
        $instructorId = Auth::id();
        
        // Ambil semua kursus yang ditugaskan kepada instruktur ini
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');
        
        // Ambil semua sertifikat dari enrollment kursus-kursus tersebut
        $certificates = Certificate::whereHas('enrollment', function ($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds);
        })
        ->with(['enrollment.course', 'enrollment.user'])
        ->orderBy('issued_at', 'desc')
        ->get();
        
        return view('instructor.dashboard.certificates', compact('certificates'));
    }

    /**
     * Generate sertifikat untuk enrollment tertentu
     */
    public function generateCertificate($enrollmentId)
    {
        $instructorId = Auth::id();
        
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);
        
        // Pastikan kursus ini ditugaskan kepada instruktur ini
        if ($enrollment->course->instructor_id !== $instructorId) {
            abort(403, 'Unauthorized');
        }
        
        // Cek apakah sudah ada sertifikat
        if ($enrollment->certificate) {
            return redirect()->route('instructor.certificates')
                ->with('error', 'Sertifikat untuk peserta ini sudah ada!');
        }
        
        // Generate sertifikat
        Certificate::issueFor($enrollment);
        
        return redirect()->route('instructor.certificates')
            ->with('success', 'Sertifikat berhasil dibuat!');
    }

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        return view('instructor.profile.index', compact('user', 'profile'));
    }

    /**
     * Update profil instructor
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        
        $profile->save();
        
        return redirect()->route('instructor.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}

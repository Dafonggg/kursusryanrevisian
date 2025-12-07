<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\detailController;
use App\Http\Controllers\listingController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseMaterialController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Instuctor\InstructorController;
use App\Http\Controllers\Instuctor\InstructorMaterialController;
use App\Http\Controllers\Instuctor\InstructorExamController;
use App\Http\Controllers\Instuctor\InstructorAnnouncementController;
use App\Http\Controllers\Admin\ExamResultController;
use App\Http\Controllers\User\StudentExamController;
use App\Http\Controllers\User\StudentAnnouncementController;
use App\Http\Controllers\User\StudentController;
use App\Http\Controllers\User\ActiveDaysCounterController;
use App\Http\Controllers\User\CertificateReadyController;
use App\Http\Controllers\User\ChatShortcutController;
use App\Http\Controllers\User\ContinueLearningController;
use App\Http\Controllers\User\PaymentStatusController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing Page Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/detail-kursus/{course:slug}', [detailController::class, 'detail'])->name('detail-kursus');
Route::get('/daftar-kursus', [listingController::class, 'listing'])->name('daftar-kursus');
Route::get('/contact', [contactController::class, 'contact'])->name('contact');
Route::get('/verifikasi-sertifikat', [LandingController::class, 'verifyCertificate'])->name('verify-certificate');
Route::post('/verifikasi-sertifikat', [LandingController::class, 'checkCertificate'])->name('check-certificate');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{courseId}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{courseId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Enrollment Routes - Hanya untuk student dan user
Route::get('/checkout', [EnrollmentController::class, 'checkout'])->name('checkout')->middleware(['auth', 'checkrole:student,user']);
Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store')->middleware(['auth', 'checkrole:student,user']);
Route::get('/enrollment/complete-data', [EnrollmentController::class, 'completeData'])->name('enrollment.complete-data')->middleware(['auth', 'checkrole:student,user']);
Route::post('/enrollment/complete-data', [EnrollmentController::class, 'storeCompleteData'])->name('enrollment.store-complete-data')->middleware(['auth', 'checkrole:student,user']);
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'registerPost'])->name('register.post');

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/google/refresh-avatar', [GoogleAuthController::class, 'refreshAvatar'])->middleware('auth')->name('auth.google.refresh-avatar');


// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::group(['middleware' => 'auth','checkrole:admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/export-financial', [AdminController::class, 'exportFinancialData'])->name('export-financial');
        Route::get('/export-financial-pdf', [AdminController::class, 'exportFinancialDataPDF'])->name('export-financial-pdf');
        
        // Courses
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course:slug}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course:slug}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course:slug}', [CourseController::class, 'destroy'])->name('courses.destroy');
        
        // Course Materials
        Route::get('/materials', [CourseMaterialController::class, 'overview'])->name('materials.overview');
        Route::get('/courses/{course:slug}/materials', [CourseMaterialController::class, 'index'])->name('materials.index');
        Route::get('/courses/{course:slug}/materials/create', [CourseMaterialController::class, 'create'])->name('materials.create');
        Route::post('/courses/{course:slug}/materials', [CourseMaterialController::class, 'store'])->name('materials.store');
        Route::get('/courses/{course:slug}/materials/{material}/edit', [CourseMaterialController::class, 'edit'])->name('materials.edit');
        Route::put('/courses/{course:slug}/materials/{material}', [CourseMaterialController::class, 'update'])->name('materials.update');
        Route::delete('/courses/{course:slug}/materials/{material}', [CourseMaterialController::class, 'destroy'])->name('materials.destroy');
        
        // Certificates
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::post('/certificates/{certificate}/approve', [CertificateController::class, 'approve'])->name('certificates.approve');
        Route::post('/certificates/{certificate}/reject', [CertificateController::class, 'reject'])->name('certificates.reject');
        Route::post('/certificates/generate/{enrollmentId}', [CertificateController::class, 'generate'])->name('certificates.generate');
        Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
        
        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/pending', [PaymentController::class, 'pending'])->name('payments.pending');
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        
        // Financial
        Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
        
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
        
        // Chat
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/create', [ChatController::class, 'create'])->name('chat.create');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/{conversation}/message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
        Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');
        
        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/course/{course:slug}', [AnalyticsController::class, 'courseDetail'])->name('analytics.course-detail');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/api', [SettingsController::class, 'getSettings'])->name('settings.api');
        
        // Profile Routes
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        
        // Exam Results Routes
        Route::get('/exam-results', [ExamResultController::class, 'index'])->name('exam-results.index');
        Route::get('/exam-results/course/{courseSlug}', [ExamResultController::class, 'courseDetail'])->name('exam-results.course');
        Route::get('/exam-results/{submission}', [ExamResultController::class, 'showSubmission'])->name('exam-results.show');
        
        // Announcements Routes
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });
});

// Instructor Routes
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'checkrole:instructor'])->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'index'])->name('dashboard');
    Route::get('/courses', [InstructorController::class, 'courses'])->name('courses');
    Route::get('/messages', [InstructorController::class, 'messages'])->name('messages');
    Route::get('/transactions', [InstructorController::class, 'transactions'])->name('transactions');
    Route::get('/certificates', [InstructorController::class, 'certificates'])->name('certificates');
    
    // Chat Routes
    Route::get('/chat/{conversationId}', [InstructorController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/create', [InstructorController::class, 'createOrGetConversation'])->name('chat.create');
    Route::post('/chat/{conversationId}/send', [InstructorController::class, 'sendMessage'])->name('chat.send');
    
    // Peserta Kursus Routes
    Route::get('/courses/{courseSlug}/students', [InstructorController::class, 'students'])->name('students');
    
    // Course Materials Routes
    Route::get('/materials', [InstructorMaterialController::class, 'overview'])->name('materials.overview');
    Route::get('/courses/{course:slug}/materials', [InstructorMaterialController::class, 'index'])->name('materials.index');
    Route::get('/courses/{course:slug}/materials/create', [InstructorMaterialController::class, 'create'])->name('materials.create');
    Route::post('/courses/{course:slug}/materials', [InstructorMaterialController::class, 'store'])->name('materials.store');
    Route::get('/courses/{course:slug}/materials/{material}/edit', [InstructorMaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/courses/{course:slug}/materials/{material}', [InstructorMaterialController::class, 'update'])->name('materials.update');
    Route::delete('/courses/{course:slug}/materials/{material}', [InstructorMaterialController::class, 'destroy'])->name('materials.destroy');
    
    // Sertifikat Routes
    Route::post('/certificates/{enrollmentId}/generate', [InstructorController::class, 'generateCertificate'])->name('certificates.generate');
    
    // Exam Routes
    Route::get('/exams', [InstructorExamController::class, 'overview'])->name('exams.overview');
    Route::get('/courses/{course:slug}/exams', [InstructorExamController::class, 'index'])->name('exams.index');
    Route::get('/courses/{course:slug}/exams/final/create', [InstructorExamController::class, 'createFinalExam'])->name('exams.create-final');
    Route::post('/courses/{course:slug}/exams/final', [InstructorExamController::class, 'storeFinalExam'])->name('exams.store-final');
    Route::get('/courses/{course:slug}/exams/practicum/create', [InstructorExamController::class, 'createPracticum'])->name('exams.create-practicum');
    Route::post('/courses/{course:slug}/exams/practicum', [InstructorExamController::class, 'storePracticum'])->name('exams.store-practicum');
    Route::get('/exams/submissions', [InstructorExamController::class, 'submissions'])->name('exams.submissions');
    Route::get('/exams/submissions/{submission}', [InstructorExamController::class, 'showSubmission'])->name('exams.show-submission');
    Route::post('/exams/submissions/{submission}/grade', [InstructorExamController::class, 'grade'])->name('exams.grade');
    Route::post('/exams/{type}/{id}/toggle', [InstructorExamController::class, 'toggleActive'])->name('exams.toggle');
    Route::delete('/exams/{type}/{id}', [InstructorExamController::class, 'destroy'])->name('exams.destroy');
    
    // Announcements Routes
    Route::get('/announcements', [InstructorAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [InstructorAnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [InstructorAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [InstructorAnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [InstructorAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [InstructorAnnouncementController::class, 'destroy'])->name('announcements.destroy');
    
    // Profile Routes
    Route::get('/profile', [InstructorController::class, 'profile'])->name('profile');
    Route::put('/profile', [InstructorController::class, 'updateProfile'])->name('profile.update');
});

// Student Routes
Route::prefix('student')->name('student.')->middleware(['auth', 'checkrole:student,user'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('my-courses');
    Route::get('/payment', [StudentController::class, 'payment'])->name('payment');
    Route::post('/payment/{payment}/upload-proof', [StudentController::class, 'uploadPaymentProof'])->name('payment.upload-proof');
    Route::get('/certificate', [StudentController::class, 'certificate'])->name('certificate');
    Route::get('/certificate/{certificate}/download', [StudentController::class, 'downloadCertificate'])->name('certificate.download');
    
    // Chat Routes
    Route::get('/chat', [StudentController::class, 'chat'])->name('chat');
    Route::post('/chat/create', [StudentController::class, 'createOrGetConversation'])->name('chat.create');
    Route::get('/chat/{conversationId}', [StudentController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/{conversationId}/message', [StudentController::class, 'sendMessage'])->name('chat.send-message');
    
    // Materials Routes
    Route::get('/materials', [StudentController::class, 'materials'])->name('materials');
    Route::get('/materials/{courseSlug}', [StudentController::class, 'materials'])->name('materials.course');
    Route::get('/materials/{courseSlug}/{materialId}', [StudentController::class, 'showMaterial'])->name('materials.show');
    Route::post('/materials/{courseSlug}/{materialId}/complete', [StudentController::class, 'completeMaterial'])->name('materials.complete');
    
    // Profile Routes
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    
    // Exam Routes
    Route::get('/exams', [StudentExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/results', [StudentExamController::class, 'results'])->name('exams.results');
    Route::get('/exams/{courseSlug}', [StudentExamController::class, 'show'])->name('exams.show');
    Route::post('/exams/{courseSlug}/submit-final', [StudentExamController::class, 'submitFinalExam'])->name('exams.submit-final');
    Route::post('/exams/{courseSlug}/submit-practicum', [StudentExamController::class, 'submitPracticum'])->name('exams.submit-practicum');
    
    // Announcements Routes
    Route::get('/announcements', [StudentAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [StudentAnnouncementController::class, 'show'])->name('announcements.show');
    
    // Dashboard Component Routes
    Route::get('/active-days-counter', [ActiveDaysCounterController::class, 'index'])->name('active-days-counter');
    Route::get('/certificate-ready', [CertificateReadyController::class, 'index'])->name('certificate-ready');
    Route::get('/chat-shortcut', [ChatShortcutController::class, 'index'])->name('chat-shortcut');
    Route::get('/continue-learning', [ContinueLearningController::class, 'index'])->name('continue-learning');
    Route::get('/payment-status', [PaymentStatusController::class, 'index'])->name('payment-status');
});
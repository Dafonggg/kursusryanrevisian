<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Enrollment;
use App\Enums\EnrollmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user();

        // Get course IDs that student is enrolled in
        $enrolledCourseIds = Enrollment::where('user_id', $student->id)
            ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
            ->pluck('course_id');

        // Get announcements: global (course_id null) OR for enrolled courses
        $query = Announcement::with(['course', 'creator'])
            ->active()
            ->published()
            ->where(function($q) use ($enrolledCourseIds) {
                $q->whereNull('course_id') // Global announcements
                  ->orWhereIn('course_id', $enrolledCourseIds); // Course-specific
            })
            ->latest('published_at');

        if ($request->filled('course')) {
            if ($request->course === 'global') {
                $query->global();
            } else {
                $query->forCourse($request->course);
            }
        }

        $announcements = $query->paginate(15);

        // Get enrolled courses for filter
        $courses = Enrollment::where('user_id', $student->id)
            ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
            ->with('course')
            ->get()
            ->pluck('course');

        return view('student.announcements.index', compact('announcements', 'courses'));
    }

    public function show(Announcement $announcement)
    {
        $student = Auth::user();

        // Verify student can view this announcement
        if ($announcement->course_id) {
            $hasAccess = Enrollment::where('user_id', $student->id)
                ->where('course_id', $announcement->course_id)
                ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Completed])
                ->exists();

            if (!$hasAccess) {
                abort(403);
            }
        }

        return view('student.announcements.show', compact('announcement'));
    }
}

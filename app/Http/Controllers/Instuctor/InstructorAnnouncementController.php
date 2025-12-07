<?php

namespace App\Http\Controllers\Instuctor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $instructor = Auth::user();
        $courses = Course::where('instructor_id', $instructor->id)->get();
        $courseIds = $courses->pluck('id');

        $query = Announcement::with(['course', 'creator'])
            ->whereIn('course_id', $courseIds)
            ->latest();

        if ($request->filled('course')) {
            $query->forCourse($request->course);
        }

        $announcements = $query->paginate(15);

        return view('instructor.announcements.index', compact('announcements', 'courses'));
    }

    public function create()
    {
        $instructor = Auth::user();
        $courses = Course::where('instructor_id', $instructor->id)->get();
        
        return view('instructor.announcements.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $instructor = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'is_active' => 'boolean',
        ]);

        // Verify instructor owns this course
        $course = Course::where('id', $validated['course_id'])
            ->where('instructor_id', $instructor->id)
            ->firstOrFail();

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'course_id' => $course->id,
            'is_active' => $request->boolean('is_active', true),
            'published_at' => now(),
            'created_by' => $instructor->id,
        ]);

        return redirect()->route('instructor.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    public function edit(Announcement $announcement)
    {
        $instructor = Auth::user();
        
        // Verify instructor owns this course
        if ($announcement->course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $courses = Course::where('instructor_id', $instructor->id)->get();
        
        return view('instructor.announcements.edit', compact('announcement', 'courses'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $instructor = Auth::user();

        // Verify instructor owns this course
        if ($announcement->course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'is_active' => 'boolean',
        ]);

        // Verify new course also belongs to instructor
        $course = Course::where('id', $validated['course_id'])
            ->where('instructor_id', $instructor->id)
            ->firstOrFail();

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'course_id' => $course->id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('instructor.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Announcement $announcement)
    {
        $instructor = Auth::user();

        // Verify instructor owns this course
        if ($announcement->course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('instructor.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}

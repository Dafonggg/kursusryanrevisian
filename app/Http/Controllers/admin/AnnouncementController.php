<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['course', 'creator'])->latest();

        if ($request->filled('course')) {
            if ($request->course === 'global') {
                $query->global();
            } else {
                $query->forCourse($request->course);
            }
        }

        $announcements = $query->paginate(15);
        $courses = Course::orderBy('title')->get();

        return view('admin.announcements.index', compact('announcements', 'courses'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.announcements.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'course_id' => $validated['course_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'published_at' => $validated['published_at'] ?? now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    public function edit(Announcement $announcement)
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.announcements.edit', compact('announcement', 'courses'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'course_id' => $validated['course_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'published_at' => $validated['published_at'],
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    /**
     * Display overview of all materials from all courses
     */
    public function overview()
    {
        $courses = Course::with(['materials', 'owner'])
            ->withCount('materials')
            ->latest()
            ->get();
        
        return view('admin.materials.overview', compact('courses'));
    }

    /**
     * Display a listing of materials for a course
     */
    public function index(Course $course)
    {
        $materials = CourseMaterial::where('course_id', $course->id)
            ->orderBy('order')
            ->get();
        
        return view('admin.materials.index', compact('course', 'materials'));
    }

    /**
     * Show the form for creating a new material
     */
    public function create(Course $course)
    {
        return view('admin.materials.create', compact('course'));
    }

    /**
     * Store a newly created material
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // Max 10MB
            'url' => 'nullable|url|max:500',
        ], [
            'title.required' => 'Judul materi wajib diisi',
            'path.file' => 'File yang diunggah tidak valid',
            'url.url' => 'URL tidak valid',
        ]);

        // Validasi: minimal harus ada path atau url (bisa keduanya)
        if (!$request->hasFile('path') && !$request->filled('url')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['path' => 'File atau URL harus diisi minimal salah satu']);
        }

        // Handle file upload (bisa upload file dan URL sekaligus)
        if ($request->hasFile('path')) {
            $validated['path'] = $request->file('path')->store('materials', 'public');
        } else {
            $validated['path'] = null;
        }

        // URL bisa diisi terlepas dari file
        if (!$request->filled('url')) {
            $validated['url'] = null;
        }

        $validated['course_id'] = $course->id;

        CourseMaterial::create($validated);

        return redirect()->route('admin.materials.index', $course->slug)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified material
     */
    public function edit(Course $course, CourseMaterial $material)
    {
        // Ensure material belongs to course
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        return view('admin.materials.edit', compact('course', 'material'));
    }

    /**
     * Update the specified material
     */
    public function update(Request $request, Course $course, CourseMaterial $material)
    {
        // Ensure material belongs to course
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240',
            'url' => 'nullable|url|max:500',
        ]);

        // Validasi: minimal harus ada path atau url (bisa keduanya, atau gunakan yang sudah ada)
        if (!$request->hasFile('path') && !$request->filled('url') && !$material->path && !$material->url) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['path' => 'File atau URL harus diisi minimal salah satu']);
        }

        // Handle file upload (bisa upload file dan URL sekaligus)
        if ($request->hasFile('path')) {
            // Delete old file if exists
            if ($material->path && Storage::disk('public')->exists($material->path)) {
                Storage::disk('public')->delete($material->path);
            }
            $validated['path'] = $request->file('path')->store('materials', 'public');
        } else {
            // Keep existing path if not uploading new file
            $validated['path'] = $material->path;
        }

        // URL bisa diisi terlepas dari file
        if (!$request->filled('url')) {
            $validated['url'] = $material->url; // Keep existing URL if not provided
        }

        $material->update($validated);

        return redirect()->route('admin.materials.index', $course->slug)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material
     */
    public function destroy(Course $course, CourseMaterial $material)
    {
        // Ensure material belongs to course
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        // Delete file if exists
        if ($material->path && Storage::disk('public')->exists($material->path)) {
            Storage::disk('public')->delete($material->path);
        }

        $material->delete();

        return redirect()->route('admin.materials.index', $course->slug)
            ->with('success', 'Materi berhasil dihapus!');
    }
}


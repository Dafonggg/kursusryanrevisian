<?php

namespace App\Http\Controllers\Instuctor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\FinalExam;
use App\Models\PracticumExam;
use App\Models\ExamSubmission;
use App\Enums\ExamStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructorExamController extends Controller
{
    public function overview()
    {
        $instructor = Auth::user();
        $courses = Course::where('instructor_id', $instructor->id)
            ->withCount(['finalExams', 'practicumExams'])
            ->get();

        $pendingSubmissions = ExamSubmission::whereHas('enrollment.course', function($q) use ($instructor) {
            $q->where('instructor_id', $instructor->id);
        })->where('status', ExamStatus::Pending)->count();

        return view('instructor.exams.overview', compact('courses', 'pendingSubmissions'));
    }

    public function index(Course $course)
    {
        $this->authorizeInstructor($course);

        $finalExams = $course->finalExams()->latest()->get();
        $practicumExams = $course->practicumExams()->latest()->get();

        return view('instructor.exams.index', compact('course', 'finalExams', 'practicumExams'));
    }

    public function createFinalExam(Course $course)
    {
        $this->authorizeInstructor($course);
        return view('instructor.exams.create-final', compact('course'));
    }

    public function storeFinalExam(Request $request, Course $course)
    {
        $this->authorizeInstructor($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'external_link' => 'nullable|url',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('exams/final', 'public');
        }

        if ($request->boolean('is_active')) {
            $course->finalExams()->update(['is_active' => false]);
        }

        FinalExam::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'external_link' => $validated['external_link'] ?? null,
            'passing_score' => $validated['passing_score'],
            'is_active' => $request->boolean('is_active'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('instructor.exams.index', $course)
            ->with('success', 'Soal ujian berhasil dibuat');
    }

    public function createPracticum(Course $course)
    {
        $this->authorizeInstructor($course);
        return view('instructor.exams.create-practicum', compact('course'));
    }

    public function storePracticum(Request $request, Course $course)
    {
        $this->authorizeInstructor($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'external_link' => 'nullable|url',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('exams/practicum', 'public');
        }

        if ($request->boolean('is_active')) {
            $course->practicumExams()->update(['is_active' => false]);
        }

        PracticumExam::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'instructions' => $validated['instructions'] ?? null,
            'file_path' => $filePath,
            'external_link' => $validated['external_link'] ?? null,
            'passing_score' => $validated['passing_score'],
            'is_active' => $request->boolean('is_active'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('instructor.exams.index', $course)
            ->with('success', 'Soal praktikum berhasil dibuat');
    }

    public function submissions()
    {
        $instructor = Auth::user();
        
        $submissions = ExamSubmission::whereHas('enrollment.course', function($q) use ($instructor) {
            $q->where('instructor_id', $instructor->id);
        })
        ->with(['enrollment.user', 'enrollment.course'])
        ->latest('submitted_at')
        ->paginate(20);

        return view('instructor.exams.submissions', compact('submissions'));
    }

    public function showSubmission(ExamSubmission $submission)
    {
        $course = $submission->enrollment->course;
        $this->authorizeInstructor($course);

        $exam = $submission->exam_type === 'final_exam' 
            ? FinalExam::find($submission->exam_id)
            : PracticumExam::find($submission->exam_id);

        return view('instructor.exams.grade', compact('submission', 'exam'));
    }

    public function grade(Request $request, ExamSubmission $submission)
    {
        $course = $submission->enrollment->course;
        $this->authorizeInstructor($course);

        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->grade($validated['score'], $validated['feedback'], Auth::user());

        return redirect()->route('instructor.exams.submissions')
            ->with('success', 'Nilai berhasil disimpan');
    }

    public function toggleActive(Request $request, $type, $id)
    {
        if ($type === 'final') {
            $exam = FinalExam::findOrFail($id);
        } else {
            $exam = PracticumExam::findOrFail($id);
        }

        $this->authorizeInstructor($exam->course);

        if (!$exam->is_active) {
            if ($type === 'final') {
                $exam->course->finalExams()->update(['is_active' => false]);
            } else {
                $exam->course->practicumExams()->update(['is_active' => false]);
            }
        }

        $exam->update(['is_active' => !$exam->is_active]);

        return back()->with('success', 'Status ujian berhasil diubah');
    }

    public function destroy($type, $id)
    {
        if ($type === 'final') {
            $exam = FinalExam::findOrFail($id);
        } else {
            $exam = PracticumExam::findOrFail($id);
        }

        $this->authorizeInstructor($exam->course);

        if ($exam->file_path) {
            Storage::disk('public')->delete($exam->file_path);
        }

        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus');
    }

    private function authorizeInstructor(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}

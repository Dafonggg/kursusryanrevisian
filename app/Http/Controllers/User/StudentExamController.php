<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FinalExam;
use App\Models\PracticumExam;
use App\Models\ExamSubmission;
use App\Enums\ExamStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentExamController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        
        $enrollments = Enrollment::where('user_id', $student->id)
            ->with(['course.activeFinalExam', 'course.activePracticumExam'])
            ->get()
            ->filter(function($enrollment) {
                return $enrollment->course->activeFinalExam || $enrollment->course->activePracticumExam;
            });

        return view('student.exams.index', compact('enrollments'));
    }

    public function show($courseSlug)
    {
        $student = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $canTakeExam = $enrollment->canTakeExam();
        $finalExam = $course->activeFinalExam;
        $practicumExam = $course->activePracticumExam;

        $finalExamSubmission = $enrollment->getFinalExamSubmission();
        $practicumSubmission = $enrollment->getPracticumSubmission();

        $materialsProgress = [
            'completed' => $enrollment->getCompletedMaterialsCount(),
            'total' => $enrollment->getTotalMaterialsCount(),
        ];

        return view('student.exams.show', compact(
            'course', 
            'enrollment', 
            'canTakeExam', 
            'finalExam', 
            'practicumExam',
            'finalExamSubmission',
            'practicumSubmission',
            'materialsProgress'
        ));
    }

    public function submitFinalExam(Request $request, $courseSlug)
    {
        $student = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        if (!$enrollment->canTakeExam()) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk mengikuti ujian');
        }

        $finalExam = $course->activeFinalExam;
        if (!$finalExam) {
            return back()->with('error', 'Tidak ada ujian yang tersedia');
        }

        $existing = $enrollment->examSubmissions()
            ->where('exam_type', 'final_exam')
            ->where('exam_id', $finalExam->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan jawaban ujian');
        }

        $validated = $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'answer_text' => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions/final', 'public');
        }

        ExamSubmission::create([
            'enrollment_id' => $enrollment->id,
            'exam_type' => 'final_exam',
            'exam_id' => $finalExam->id,
            'file_path' => $filePath,
            'answer_text' => $validated['answer_text'] ?? null,
            'status' => ExamStatus::Pending,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Jawaban ujian berhasil dikumpulkan');
    }

    public function submitPracticum(Request $request, $courseSlug)
    {
        $student = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        if (!$enrollment->canTakeExam()) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk mengikuti praktikum');
        }

        $practicumExam = $course->activePracticumExam;
        if (!$practicumExam) {
            return back()->with('error', 'Tidak ada praktikum yang tersedia');
        }

        $existing = $enrollment->examSubmissions()
            ->where('exam_type', 'practicum')
            ->where('exam_id', $practicumExam->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan jawaban praktikum');
        }

        // Jika praktikum via link eksternal, file tidak wajib
        $isExternalOnly = $practicumExam->external_link && !$practicumExam->file_path;
        
        $validated = $request->validate([
            'file' => $isExternalOnly ? 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:20480' : 'required|file|mimes:pdf,doc,docx,zip,rar|max:20480',
            'answer_text' => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions/practicum', 'public');
        }

        ExamSubmission::create([
            'enrollment_id' => $enrollment->id,
            'exam_type' => 'practicum',
            'exam_id' => $practicumExam->id,
            'file_path' => $filePath,
            'answer_text' => $validated['answer_text'] ?? null,
            'status' => ExamStatus::Pending,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Jawaban praktikum berhasil dikumpulkan');
    }

    public function results()
    {
        $student = Auth::user();
        
        $submissions = ExamSubmission::whereHas('enrollment', function($q) use ($student) {
            $q->where('user_id', $student->id);
        })
        ->with(['enrollment.course'])
        ->latest('submitted_at')
        ->get();

        return view('student.exams.results', compact('submissions'));
    }
}

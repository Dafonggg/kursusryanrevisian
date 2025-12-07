<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamSubmission;
use App\Models\FinalExam;
use App\Models\PracticumExam;
use App\Enums\ExamStatus;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamSubmission::with(['enrollment.user', 'enrollment.course']);

        if ($request->filled('course')) {
            $query->whereHas('enrollment.course', function($q) use ($request) {
                $q->where('slug', $request->course);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('exam_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('enrollment.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest('submitted_at')->paginate(20);
        $courses = Course::orderBy('title')->get();

        $stats = [
            'total' => ExamSubmission::count(),
            'pending' => ExamSubmission::where('status', ExamStatus::Pending)->count(),
            'passed' => ExamSubmission::where('status', ExamStatus::Passed)->count(),
            'failed' => ExamSubmission::where('status', ExamStatus::Failed)->count(),
        ];

        return view('admin.exams.results', compact('submissions', 'courses', 'stats'));
    }

    public function courseDetail($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        $finalExam = $course->activeFinalExam;
        $practicumExam = $course->activePracticumExam;

        $finalSubmissions = collect();
        $practicumSubmissions = collect();

        if ($finalExam) {
            $finalSubmissions = ExamSubmission::where('exam_type', 'final_exam')
                ->where('exam_id', $finalExam->id)
                ->with(['enrollment.user'])
                ->latest('submitted_at')
                ->get();
        }

        if ($practicumExam) {
            $practicumSubmissions = ExamSubmission::where('exam_type', 'practicum')
                ->where('exam_id', $practicumExam->id)
                ->with(['enrollment.user'])
                ->latest('submitted_at')
                ->get();
        }

        $stats = [
            'final' => [
                'total' => $finalSubmissions->count(),
                'pending' => $finalSubmissions->where('status', ExamStatus::Pending)->count(),
                'passed' => $finalSubmissions->where('status', ExamStatus::Passed)->count(),
                'failed' => $finalSubmissions->where('status', ExamStatus::Failed)->count(),
                'avg_score' => $finalSubmissions->whereNotNull('score')->avg('score'),
            ],
            'practicum' => [
                'total' => $practicumSubmissions->count(),
                'pending' => $practicumSubmissions->where('status', ExamStatus::Pending)->count(),
                'passed' => $practicumSubmissions->where('status', ExamStatus::Passed)->count(),
                'failed' => $practicumSubmissions->where('status', ExamStatus::Failed)->count(),
                'avg_score' => $practicumSubmissions->whereNotNull('score')->avg('score'),
            ],
        ];

        return view('admin.exams.course-detail', compact(
            'course', 
            'finalExam', 
            'practicumExam',
            'finalSubmissions', 
            'practicumSubmissions',
            'stats'
        ));
    }

    public function showSubmission(ExamSubmission $submission)
    {
        $submission->load(['enrollment.user', 'enrollment.course', 'grader']);

        $exam = $submission->exam_type === 'final_exam' 
            ? FinalExam::find($submission->exam_id)
            : PracticumExam::find($submission->exam_id);

        return view('admin.exams.show-submission', compact('submission', 'exam'));
    }
}

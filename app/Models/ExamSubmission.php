<?php

namespace App\Models;

use App\Enums\ExamStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'exam_type',
        'exam_id',
        'file_path',
        'answer_text',
        'score',
        'feedback',
        'status',
        'submitted_at',
        'graded_at',
        'graded_by',
    ];

    protected $casts = [
        'status' => ExamStatus::class,
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function exam()
    {
        if ($this->exam_type === 'final_exam') {
            return $this->belongsTo(FinalExam::class, 'exam_id');
        }
        return $this->belongsTo(PracticumExam::class, 'exam_id');
    }

    public function finalExam()
    {
        return $this->belongsTo(FinalExam::class, 'exam_id');
    }

    public function practicumExam()
    {
        return $this->belongsTo(PracticumExam::class, 'exam_id');
    }

    public function isPassed(): bool
    {
        return $this->status === ExamStatus::Passed;
    }

    public function grade(int $score, ?string $feedback, User $grader): void
    {
        $exam = $this->exam_type === 'final_exam' 
            ? FinalExam::find($this->exam_id)
            : PracticumExam::find($this->exam_id);

        $status = $score >= $exam->passing_score ? ExamStatus::Passed : ExamStatus::Failed;

        $this->update([
            'score' => $score,
            'feedback' => $feedback,
            'status' => $status,
            'graded_at' => now(),
            'graded_by' => $grader->id,
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', ExamStatus::Pending);
    }

    public function scopeGraded($query)
    {
        return $query->whereIn('status', [ExamStatus::Passed, ExamStatus::Failed, ExamStatus::Graded]);
    }
}

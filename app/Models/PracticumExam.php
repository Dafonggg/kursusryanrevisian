<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PracticumExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'instructions',
        'file_path',
        'external_link',
        'passing_score',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class, 'exam_id')
            ->where('exam_type', 'practicum');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

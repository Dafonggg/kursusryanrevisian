<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'path',     // file lokal
        'url',      // link video / drive / youtube
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relationship ke MaterialProgress
     */
    public function progresses()
    {
        return $this->hasMany(MaterialProgress::class, 'material_id');
    }

    /** Urutkan materi awal */
    protected static function booted()
    {
        static::creating(function ($material) {
            if ($material->order === null) {
                $material->order = static::where('course_id', $material->course_id)->max('order') + 1;
            }
        });
    }
}

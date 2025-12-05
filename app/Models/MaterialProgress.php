<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'material_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Relationship ke Enrollment
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Relationship ke CourseMaterial
     */
    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'material_id');
    }

    /**
     * Cek apakah materi sudah completed
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Mark materi sebagai completed
     */
    public function markAsCompleted(): void
    {
        if (!$this->isCompleted()) {
            $this->update(['completed_at' => now()]);
        }
    }
}

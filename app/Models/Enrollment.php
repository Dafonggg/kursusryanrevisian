<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'started_at',
        'expires_at',
        'status',
        'modality',     // online | offline (kalau kamu pakai batch pilihan)
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'status'     => EnrollmentStatus::class,
    ];

    /* ======================
       RELATIONSHIPS
       ====================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function materialProgresses()
    {
        return $this->hasMany(MaterialProgress::class);
    }

    /* ======================
       LOGIC BAWAAN (PENTING)
       ====================== */

    /**
     * Aktifkan pendaftaran.
     * Dipanggil saat admin menekan tombol "Aktifkan".
     */
    public function activate(?Carbon $start = null): void
    {
        $start = $start ?? now();

        $this->update([
            'started_at' => $start,
            'expires_at' => $start->copy()->addMonths($this->course->duration_months ?? 3),
            'status'     => EnrollmentStatus::Active,
        ]);
    }

    /**
     * Cek apakah pendaftaran sudah kadaluarsa.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at !== null && now()->greaterThan($this->expires_at);
    }

    /* ======================
       SCOPES (Filter Cepat)
       ====================== */

    public function scopeActive($q)
    {
        return $q->where('status', EnrollmentStatus::Active);
    }

    public function scopePending($q)
    {
        return $q->where('status', EnrollmentStatus::Pending);
    }

    /* ======================
       MATERIAL PROGRESS METHODS
       ====================== */

    /**
     * Get jumlah materi yang sudah completed
     */
    public function getCompletedMaterialsCount(): int
    {
        return $this->materialProgresses()
            ->whereNotNull('completed_at')
            ->count();
    }

    /**
     * Get total jumlah materi di kursus
     */
    public function getTotalMaterialsCount(): int
    {
        return $this->course->materials()->count();
    }

    /**
     * Cek apakah semua materi sudah completed
     */
    public function isAllMaterialsCompleted(): bool
    {
        $total = $this->getTotalMaterialsCount();
        if ($total === 0) {
            return false;
        }
        return $this->getCompletedMaterialsCount() === $total;
    }

    /**
     * Cek dan update status enrollment menjadi completed jika semua materi selesai
     */
    public function checkAndMarkCompleted(): bool
    {
        if ($this->isAllMaterialsCompleted() && $this->status !== EnrollmentStatus::Completed) {
            $this->update(['status' => EnrollmentStatus::Completed]);
            return true;
        }
        return false;
    }
}

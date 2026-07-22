<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at'  => 'datetime',
        'completed_at' => 'datetime',
    ];

    /* ---------------- RELATIONSHIPS ---------------- */

    // The student who enrolled
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // The course they enrolled in
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // All lesson-progress rows for this enrollment
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    // The certificate (only exists once completed) — one enrollment, one cert
    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    /* ---------------- HELPERS ---------------- */

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Progress % — CALCULATED, never stored (our locked design principle)
    //
    // Ye view me har enrollment ke liye loop me call hota hai. Agar controller ne
    // counts pehle se load kar diye (withCount) to yahan ZERO query lagti hai.
    // Warna purana behaviour — query karke nikal lega, taaki kahin bhi safe rahe.
    public function progressPercent(): int
    {
        $totalLessons = $this->preloadedTotalLessons() ?? $this->course->lessons()->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $completed = $this->completed_lessons_count
            ?? $this->lessonProgress()->where('status', 'completed')->count();

        return (int) round(($completed / $totalLessons) * 100);
    }

    /**
     * Controller ne `course` ko withCount('lessons') ke saath load kiya hai?
     * Ho to wahi count use karo — na ho to null, caller query kar lega.
     */
    private function preloadedTotalLessons(): ?int
    {
        if (! $this->relationLoaded('course') || $this->course === null) {
            return null;
        }

        return isset($this->course->lessons_count)
            ? (int) $this->course->lessons_count
            : null;
    }
}

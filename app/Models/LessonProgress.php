<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    // ⚠️ CRITICAL: without this, Laravel looks for a 'lesson_progresses' table and breaks.
    // Our table is 'lesson_progress' (singular), so we pin it explicitly.
    protected $table = 'lesson_progress';

    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'status',
        'watched_seconds',
        'completed_at',
    ];

    protected $casts = [
        'watched_seconds' => 'integer',
        'completed_at'    => 'datetime',
    ];

    /* ---------------- RELATIONSHIPS ---------------- */

    // Progress belongs to an enrollment (which knows the user + course)
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Progress belongs to a lesson
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /* ---------------- HELPERS ---------------- */

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
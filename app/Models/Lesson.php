<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'type',
        'duration_minutes',
        'sort_order',
        'video_path',
        'video_duration_seconds',
        'file_path',
    ];

    protected $casts = [
        'duration_minutes'       => 'integer',
        'sort_order'             => 'integer',
        'video_duration_seconds' => 'integer',
    ];

    /* ---------------- RELATIONSHIPS ---------------- */

    // Lesson belongs to a module
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // Progress records for this lesson (one per enrolled student who touched it)
    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    /* ---------------- HELPERS ---------------- */

    // Quick type checks for Blade (@if($lesson->isVideo()) show player @else show pdf)
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isPdf(): bool
    {
        return $this->type === 'pdf';
    }
}
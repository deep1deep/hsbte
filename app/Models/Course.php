<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    protected $fillable = [
        'department_id',
        'trainer_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'duration_weeks',
        'status',
        'cert_mode',
        'is_paid',
        'price',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'price'   => 'integer',
    ];

    /* ---------------- RELATIONSHIPS ---------------- */

    // Course belongs to a department
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Course is taught by a trainer (a user)
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    // Course has many modules (Week 1, Week 2...)
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    // Course has many enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Course has many student reviews
    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    /* ---------------- RATINGS ---------------- */

    // Average star rating. Uses withAvg('reviews','rating') if preloaded (no query),
    // else falls back to a direct query — safe to call anywhere.
    public function averageRating(): float
    {
        $avg = $this->reviews_avg_rating ?? $this->reviews()->avg('rating');

        return round((float) $avg, 1);
    }

    // How many reviews. Uses withCount('reviews') if preloaded.
    public function reviewsCount(): int
    {
        return (int) ($this->reviews_count ?? $this->reviews()->count());
    }

    // Saare lessons — modules ke through, ek hi query me.
    // Isse total-lesson count nikalna 1 query hai, module-by-module loop nahi.
    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }

    /* ---------------- CERTIFICATE MODE ---------------- */

    // manual = trainer har student ka file upload karega
    public function usesManualCertificates(): bool
    {
        return $this->cert_mode === 'manual';
    }

    // auto = trainer ke HTML template se apne aap banega
    public function usesAutoCertificates(): bool
    {
        return $this->cert_mode === 'auto';
    }

    /* ---------------- ROUTE MODEL BINDING ---------------- */

    // Makes /courses/{course:slug} work — Laravel looks up by slug not id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
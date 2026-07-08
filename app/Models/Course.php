<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /* ---------------- ROUTE MODEL BINDING ---------------- */

    // Makes /courses/{course:slug} work — Laravel looks up by slug not id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
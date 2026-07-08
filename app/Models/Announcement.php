<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
    ];

    /* ---------------- QUERY SCOPES ---------------- */

    // Only active announcements, newest first.
    // Usage in controller: Announcement::active()->get();
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
                     ->orderByDesc('published_at');
    }
}
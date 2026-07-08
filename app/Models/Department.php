<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // A department has many courses
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // A department has many users (students belong to a department via department_id)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
        'enrollment_no',
        'institute',
        'semester',
        'department_id',
        'designation',
        'qualification',
        'aadhaar_hash',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'aadhaar_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /* ---------------- RELATIONSHIPS ---------------- */

    // Student/trainer belongs to a department (via department_id)
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Courses this user teaches (trainer_id on courses points here)
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    // Enrollments this user has as a student
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Trainer ka certificate HTML design (auto mode ke liye)
    public function certificateTemplate(): HasOne
    {
        return $this->hasOne(CertificateTemplate::class, 'trainer_id')->where('is_active', true);
    }

    /* ---------------- ROLE HELPERS ---------------- */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'enrollment_id',
        'certificate_no',
        'file_path',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    /* ---------------- RELATIONSHIPS ---------------- */

    // Certificate belongs to one completed enrollment
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
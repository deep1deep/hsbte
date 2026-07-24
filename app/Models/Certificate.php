<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'enrollment_id',
        'certificate_no',
        'status',
        'source',
        'file_path',
        'file_blob',
        'file_mime',
        'uploaded_by',
        'template_snapshot',
        'issued_at',
    ];

    protected $casts = [
        'issued_at'         => 'datetime',
        'template_snapshot' => 'array',
    ];

    // Never dump/serialize the blob — otherwise a debug page prints a 1MB+ string and eats up memory
    protected $hidden = [
        'file_blob',
    ];

    /* ---------------- QUERY SCOPES ---------------- */

    /**
     * Every column EXCEPT file_blob.
     *
     * $hidden only affects serialization — the blob is still fetched in the SELECT
     * and eats up memory (each blob ~6.7MB). This scope is needed for listing
     * pages; download() loads the full model, so the blob is available there.
     *
     * Usage: Certificate::withoutBlob()->get()
     */
    public function scopeWithoutBlob(Builder $query): Builder
    {
        return $query->select([
            'id',
            'enrollment_id',
            'certificate_no',
            'status',
            'source',
            'file_path',
            'file_mime',
            'uploaded_by',
            'template_snapshot',
            'issued_at',
            'created_at',
            'updated_at',
        ]);
    }

    /* ---------------- RELATIONSHIPS ---------------- */

    // Certificate belongs to an enrollment
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Which trainer uploaded it (manual mode)
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isIssued(): bool
    {
        return $this->status === 'issued';
    }

    public function isManual(): bool
    {
        return $this->source === 'manual';
    }
}
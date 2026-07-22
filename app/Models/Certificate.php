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

    // blob kabhi dump/serialize na ho — warna debug page 1MB+ string print karke memory kha jaata hai
    protected $hidden = [
        'file_blob',
    ];

    /* ---------------- QUERY SCOPES ---------------- */

    /**
     * Har column CHHOD KE file_blob.
     *
     * $hidden sirf serialize pe asar karta hai — SELECT me blob phir bhi aata hai
     * aur memory kha jaata hai (ek blob ~6.7MB). Listing pages ke liye ye scope
     * zaroori hai; download() poora model leta hai isliye wahan blob milega.
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

    // Kis trainer ne upload kiya (manual mode)
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
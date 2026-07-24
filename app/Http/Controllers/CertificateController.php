<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\UniqueConstraintViolationException;

class CertificateController extends Controller
{
    /**
     * Ensure a certificate exists for an enrollment (create one if missing).
     * This is called from StudentController on course completion.
     *
     * The trainer's cert_mode decides the behaviour:
     *   manual -> row is created PENDING (number reserved, no PDF) — trainer uploads it
     *   auto   -> row is created ISSUED (dompdf renders on download)
     */
    public static function generateFor(Enrollment $enrollment): Certificate
    {
        // If one already exists, return it (avoid duplicates)
        if ($enrollment->certificate) {
            return $enrollment->certificate;
        }

        // Determine which trainer owns this course
        $enrollment->loadMissing('course');
        $isManual = $enrollment->course->usesManualCertificates();

        $year = now()->year;

        // Deriving the number and inserting are retried together as one unit.
        // Previously the number was derived with count() and inserted separately — if two
        // students completed a course at the same time, both received the same number and one
        // got a 500 error. Now the DB blocks the collision and we take the next number.
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $count  = Certificate::whereYear('issued_at', $year)->count() + 1 + $attempt;
            $number = sprintf('HSBTE-%d-%06d', $year, $count);

            while (Certificate::where('certificate_no', $number)->exists()) {
                $count++;
                $number = sprintf('HSBTE-%d-%06d', $year, $count);
            }

            try {
                return Certificate::create([
                    'enrollment_id'  => $enrollment->id,
                    'certificate_no' => $number,
                    'status'         => $isManual ? 'pending' : 'issued',
                    'source'         => $isManual ? 'manual' : 'auto',
                    'issued_at'      => now(),   // time the number was reserved
                ]);
            } catch (UniqueConstraintViolationException $e) {
                // Two possibilities:
                //  - enrollment_id clash → student double-clicked, the cert already exists
                //  - certificate_no clash → another student took the same number, retry
                $existing = $enrollment->fresh()->certificate;

                if ($existing) {
                    return $existing;
                }
            }
        }

        throw new \RuntimeException('Could not allocate a unique certificate number after 5 attempts.');
    }

    /**
     * Download certificate (only your own certificate).
     *   manual -> streams the base64 PDF stored in the DB
     *   auto   -> dompdf renders it live
     */
    public function download(Certificate $certificate)
    {
        $enrollment = $certificate->enrollment;

        // security: only the owner may download their certificate
        abort_unless($enrollment->user_id === auth()->id(), 403);

        // trainer has not issued it yet
        if ($certificate->isPending()) {
            return back()->with('error', 'Your certificate is currently pending with the trainer. The download link will be available as soon as it is issued.');
        }

        // MANUAL — the file uploaded by the trainer
        if ($certificate->isManual()) {
            abort_if(empty($certificate->file_blob), 404, 'Certificate file not found.');

            $mime   = $certificate->file_mime ?: 'application/pdf';
            $ext    = match ($mime) {
                'image/png'  => 'png',
                'image/jpeg' => 'jpg',
                default      => 'pdf',
            };
            $binary = base64_decode($certificate->file_blob);

            return response($binary, 200, [
                'Content-Type'        => $mime,
                'Content-Disposition' => 'attachment; filename="' . $certificate->certificate_no . '.' . $ext . '"',
                'Content-Length'      => strlen($binary),
            ]);
        }

        // AUTO — the current dompdf flow
        $enrollment->load(['user', 'course']);

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'student'     => $enrollment->user,
            'course'      => $enrollment->course,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($certificate->certificate_no . '.pdf');
    }

    /**
     * Public verify page — anyone (without login) can enter a number to check.
     * Only ISSUED certificates are verifiable — not pending ones.
     */
    public function verify(\Illuminate\Http\Request $request)
    {
        $number = trim($request->query('number', ''));
        $certificate = null;
        $searched = false;

        if ($number !== '') {
            $searched = true;
            // public endpoint — the blob is never needed here, so it's excluded from the SELECT
            $certificate = Certificate::withoutBlob()
                ->where('certificate_no', $number)
                ->where('status', 'issued')
                ->with(['enrollment.user', 'enrollment.course'])
                ->first();
        }

        return view('certificates.verify', compact('certificate', 'number', 'searched'));
    }
}
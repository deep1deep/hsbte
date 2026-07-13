<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Ek enrollment ke liye certificate ensure karo (na ho to banao).
     * Ye StudentController se course-complete pe call hoga.
     */
    public static function generateFor(Enrollment $enrollment): Certificate
    {
        // pehle se hai to wahi wapas (double na bane)
        if ($enrollment->certificate) {
            return $enrollment->certificate;
        }

        // unique number: HSBTE-2026-000001
        $year   = now()->year;
        $count  = Certificate::whereYear('issued_at', $year)->count() + 1;
        $number = sprintf('HSBTE-%d-%06d', $year, $count);

        // agar kabhi collision ho (race) to +1 karke bacho
        while (Certificate::where('certificate_no', $number)->exists()) {
            $count++;
            $number = sprintf('HSBTE-%d-%06d', $year, $count);
        }

        return Certificate::create([
            'enrollment_id'  => $enrollment->id,
            'certificate_no' => $number,
            'issued_at'      => now(),
        ]);
    }

    /**
     * Download certificate PDF (sirf apna certificate).
     */
    public function download(Certificate $certificate)
    {
        $enrollment = $certificate->enrollment;

        // security: sirf jiska certificate hai wahi download kare
        abort_unless($enrollment->user_id === auth()->id(), 403);

        $enrollment->load(['user', 'course']);

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'student'     => $enrollment->user,
            'course'      => $enrollment->course,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($certificate->certificate_no . '.pdf');
    }

    /**
     * Public verify page — koi bhi (bina login) number daal ke check kare.
     */
    public function verify(\Illuminate\Http\Request $request)
    {
        $number = trim($request->query('number', ''));
        $certificate = null;
        $searched = false;

        if ($number !== '') {
            $searched = true;
            $certificate = Certificate::where('certificate_no', $number)
                ->with(['enrollment.user', 'enrollment.course'])
                ->first();
        }

        return view('certificates.verify', compact('certificate', 'number', 'searched'));
    }
}
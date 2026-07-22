<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\UniqueConstraintViolationException;

class CertificateController extends Controller
{
    /**
     * Ek enrollment ke liye certificate ensure karo (na ho to banao).
     * Ye StudentController se course-complete pe call hoga.
     *
     * Trainer ka cert_mode decide karta hai:
     *   manual -> row banegi PENDING (number reserve, PDF nahi) — trainer upload karega
     *   auto   -> row banegi ISSUED (dompdf download pe render hoga)
     */
    public static function generateFor(Enrollment $enrollment): Certificate
    {
        // pehle se hai to wahi wapas (double na bane)
        if ($enrollment->certificate) {
            return $enrollment->certificate;
        }

        // is course ka trainer kaun hai
        $enrollment->loadMissing('course');
        $isManual = $enrollment->course->usesManualCertificates();

        $year = now()->year;

        // Number nikalna aur insert karna EK saath retry hota hai.
        // Pehle count() se number nikal ke alag se insert hota tha — do students ek
        // saath course complete karein to dono ko same number milta tha aur ek ko
        // 500 error. Ab collision pe DB rok deta hai aur hum agla number le lete hain.
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
                    'issued_at'      => now(),   // number reserve hone ka time
                ]);
            } catch (UniqueConstraintViolationException $e) {
                // Do possibilities:
                //  - enrollment_id clash → student ne double-click kiya, cert ban chuka
                //  - certificate_no clash → koi aur student wahi number le gaya, retry
                $existing = $enrollment->fresh()->certificate;

                if ($existing) {
                    return $existing;
                }
            }
        }

        throw new \RuntimeException('Could not allocate a unique certificate number after 5 attempts.');
    }

    /**
     * Download certificate (sirf apna certificate).
     *   manual -> DB me rakha base64 PDF stream hoga
     *   auto   -> dompdf live render karega
     */
    public function download(Certificate $certificate)
    {
        $enrollment = $certificate->enrollment;

        // security: sirf jiska certificate hai wahi download kare
        abort_unless($enrollment->user_id === auth()->id(), 403);

        // trainer ne abhi issue nahi kiya
        if ($certificate->isPending()) {
            return back()->with('error', 'Aapka certificate abhi trainer ke paas pending hai. Issue hote hi download link aa jaayega.');
        }

        // MANUAL — trainer ka upload kiya file
        if ($certificate->isManual()) {
            abort_if(empty($certificate->file_blob), 404, 'Certificate file nahi mili.');

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

        // AUTO — abhi wala dompdf flow
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
     * Sirf ISSUED certificate verify hote hain — pending wale nahi.
     */
    public function verify(\Illuminate\Http\Request $request)
    {
        $number = trim($request->query('number', ''));
        $certificate = null;
        $searched = false;

        if ($number !== '') {
            $searched = true;
            // public endpoint — blob yahan kabhi chahiye nahi, isliye SELECT se bahar
            $certificate = Certificate::withoutBlob()
                ->where('certificate_no', $number)
                ->where('status', 'issued')
                ->with(['enrollment.user', 'enrollment.course'])
                ->first();
        }

        return view('certificates.verify', compact('certificate', 'number', 'searched'));
    }
}
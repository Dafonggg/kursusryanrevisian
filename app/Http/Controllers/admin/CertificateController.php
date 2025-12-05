<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Enums\EnrollmentStatus;
use App\Enums\CertificateStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['enrollment.course', 'enrollment.user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', CertificateStatus::from($request->status));
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Approve certificate
     */
    public function approve(Certificate $certificate)
    {
        $certificate->approve();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil disetujui!');
    }

    /**
     * Reject certificate
     */
    public function reject(Certificate $certificate)
    {
        $certificate->reject();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat ditolak.');
    }

    /**
     * Generate certificate PDF
     */
    public function generate($enrollmentId)
    {
        $enrollment = Enrollment::with(['course', 'user'])->findOrFail($enrollmentId);

        // Cek apakah enrollment sudah completed
        if ($enrollment->status !== EnrollmentStatus::Completed) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Enrollment harus dalam status completed untuk generate sertifikat.');
        }

        // Cek apakah sertifikat sudah ada, jika tidak buat baru
        $certificate = $enrollment->certificate;
        if (!$certificate) {
            $certificate = Certificate::issueFor($enrollment);
        }

        // Generate PDF
        $pdf = Pdf::loadView('certificates.template', compact('certificate'));
        $pdf->setPaper('a4', 'landscape');
        
        // Simpan PDF ke storage
        $fileName = 'certificates/' . $certificate->certificate_no . '.pdf';
        Storage::put($fileName, $pdf->output());
        
        // Update file_path di database
        $certificate->update(['file_path' => $fileName]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'PDF sertifikat berhasil di-generate!');
    }

    /**
     * Download certificate PDF
     */
    public function download(Certificate $certificate)
    {
        // Cek apakah sertifikat sudah approved
        if (!$certificate->isApproved()) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Sertifikat belum disetujui.');
        }

        // Cek apakah file PDF sudah ada
        if (!$certificate->file_path || !Storage::exists($certificate->file_path)) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'File PDF sertifikat belum tersedia. Silakan generate PDF terlebih dahulu.');
        }

        return Storage::download($certificate->file_path, 'certificate-' . $certificate->certificate_no . '.pdf');
    }
}

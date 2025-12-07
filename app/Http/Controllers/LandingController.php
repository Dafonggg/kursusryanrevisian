<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use App\Enums\CourseMode;
use App\Enums\CertificateStatus;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Default menampilkan kursus online
        $courses = Course::with(['owner', 'instructor'])
            ->withCount('enrollments')
            ->online()
            ->latest()
            ->get();
        
        // Hitung total kursus untuk badge
        $totalCourses = Course::count();
        
        return view('landing.index', compact('courses', 'totalCourses'));
    }

    /**
     * Display the topics detail page.
     */
    /**
     * Display the main page with all content combined.
     */
    public function main()
    {
        return view('landing.main2');
    }

    /**
     * Display certificate verification form
     */
    public function verifyCertificate()
    {
        return view('landing.verify-certificate');
    }

    /**
     * Check certificate authenticity
     */
    public function checkCertificate(Request $request)
    {
        $request->validate([
            'certificate_no' => 'required|string|max:50',
        ]);

        $certificateNo = strtoupper(trim($request->certificate_no));
        
        $certificate = Certificate::with(['enrollment.user', 'enrollment.course'])
            ->where('certificate_no', $certificateNo)
            ->first();

        $result = null;
        if ($certificate && $certificate->status === CertificateStatus::Approved) {
            $result = [
                'valid' => true,
                'certificate_no' => $certificate->certificate_no,
                'student_name' => $certificate->enrollment->user->name,
                'course_name' => $certificate->enrollment->course->title,
                'issued_at' => $certificate->issued_at->format('d F Y'),
            ];
        } elseif ($certificate && $certificate->status !== CertificateStatus::Approved) {
            $result = [
                'valid' => false,
                'message' => 'Sertifikat ditemukan tetapi belum disetujui atau ditolak.',
            ];
        } else {
            $result = [
                'valid' => false,
                'message' => 'Nomor sertifikat tidak ditemukan dalam sistem.',
            ];
        }

        return view('landing.verify-certificate', [
            'result' => $result,
            'searched' => $certificateNo,
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

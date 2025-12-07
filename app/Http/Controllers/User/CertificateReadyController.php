<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateReadyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $certificates = Certificate::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['enrollment.course'])
            ->latest('issued_at')
            ->get();
        
        $ready_certificates = [];
        foreach ($certificates as $cert) {
            $course = $cert->enrollment->course;
            $ready_certificates[] = (object)[
                'id' => $cert->id,
                'course_name' => $course->title,
                'course_category' => 'Kursus',
                'course_image' => $course->image ? Storage::url($course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                'certificate_date' => $cert->issued_at ? $cert->issued_at->format('d M Y') : '-',
                'certificate_url' => $cert->file_path ? route('student.certificate.download', $cert->id) : '#',
            ];
        }
        
        $ready_certificates_count = count($ready_certificates);

        return view('student.dashboard.components.certificate-ready', compact(
            'ready_certificates',
            'ready_certificates_count'
        ));
    }
}


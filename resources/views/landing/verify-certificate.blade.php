@extends('layouts.app')

@section('title', 'Verifikasi Sertifikat - Kursus Ryan Komputer')

@section('content')
<header class="site-header" style="padding-top: 100px; padding-bottom: 40px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h1 class="text-white mb-0" style="font-size: 2rem;">Verifikasi Sertifikat</h1>
                <p class="text-white mt-2">Masukkan nomor sertifikat untuk memverifikasi keasliannya</p>
            </div>
        </div>
    </div>
</header>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <span class="material-symbols-outlined" style="font-size: 64px; color: #667eea;">verified</span>
                            <h4 class="mt-3">Cek Keaslian Sertifikat</h4>
                            <p class="text-muted">Format nomor: RK-YYYY-MM-XXXXXX</p>
                        </div>

                        <form action="{{ route('check-certificate') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="certificate_no" class="form-label fw-bold">Nomor Sertifikat</label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('certificate_no') is-invalid @enderror" 
                                       id="certificate_no" 
                                       name="certificate_no" 
                                       placeholder="Contoh: RK-2025-01-ABC123"
                                       value="{{ $searched ?? old('certificate_no') }}"
                                       style="text-transform: uppercase;"
                                       required>
                                @error('certificate_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn custom-btn w-100 py-3">
                                <span class="material-symbols-outlined me-2" style="vertical-align: middle;">search</span>
                                Verifikasi Sertifikat
                            </button>
                        </form>

                        @if(isset($result))
                            <hr class="my-4">
                            
                            @if($result['valid'])
                                <div class="alert alert-success border-0 shadow-sm">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="material-symbols-outlined me-2" style="font-size: 32px; color: #198754;">check_circle</span>
                                        <h5 class="mb-0 text-success">Sertifikat Valid</h5>
                                    </div>
                                    <p class="mb-0 text-muted">Sertifikat ini terdaftar dan sah.</p>
                                </div>

                                <div class="card bg-light border-0 mt-3">
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted" style="width: 40%;">Nomor Sertifikat</td>
                                                    <td class="fw-bold">{{ $result['certificate_no'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Nama Pemegang</td>
                                                    <td class="fw-bold">{{ $result['student_name'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Nama Kursus</td>
                                                    <td class="fw-bold">{{ $result['course_name'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Tanggal Terbit</td>
                                                    <td class="fw-bold">{{ $result['issued_at'] }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger border-0 shadow-sm">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="material-symbols-outlined me-2" style="font-size: 32px; color: #dc3545;">cancel</span>
                                        <h5 class="mb-0 text-danger">Sertifikat Tidak Valid</h5>
                                    </div>
                                    <p class="mb-0">{{ $result['message'] }}</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">info</span>
                        Jika Anda mengalami masalah dalam verifikasi, silakan <a href="{{ route('contact') }}">hubungi kami</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

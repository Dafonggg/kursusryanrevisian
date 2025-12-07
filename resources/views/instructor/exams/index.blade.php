@extends('instructor.layouts.master')

@section('title', 'Kelola Ujian - ' . $course->title)

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>{{ $course->title }} - Kelola Ujian</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('instructor.exams.overview') }}" class="btn btn-light-primary me-3">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-5 g-xl-10">
                <!-- Soal Ujian -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">Soal Ujian</h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="{{ route('instructor.exams.create-final', $course) }}" class="btn btn-sm btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Tambah Soal
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse($finalExams as $exam)
                                <div class="d-flex align-items-center mb-5 p-4 border rounded {{ $exam->is_active ? 'border-success' : '' }}">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-gray-800 fs-6">{{ $exam->title }}</span>
                                            @if($exam->is_active)
                                                <span class="badge badge-success ms-2">Aktif</span>
                                            @endif
                                        </div>
                                        <div class="text-muted fs-7 mt-1">
                                            Nilai Minimum: {{ $exam->passing_score }}
                                        </div>
                                        @if($exam->file_path)
                                            <a href="{{ asset('storage/' . $exam->file_path) }}" target="_blank" class="text-primary fs-7">
                                                <i class="ki-duotone ki-file fs-6"></i> Lihat File
                                            </a>
                                        @endif
                                        @if($exam->external_link)
                                            <a href="{{ $exam->external_link }}" target="_blank" class="text-primary fs-7 ms-3">
                                                <i class="ki-duotone ki-external-link fs-6"></i> Link Eksternal
                                            </a>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('instructor.exams.toggle', ['type' => 'final', 'id' => $exam->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-{{ $exam->is_active ? 'warning' : 'success' }}" title="{{ $exam->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="ki-duotone ki-{{ $exam->is_active ? 'cross' : 'check' }} fs-2"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('instructor.exams.destroy', ['type' => 'final', 'id' => $exam->id]) }}" method="POST" onsubmit="return confirm('Hapus soal ujian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger">
                                                <i class="ki-duotone ki-trash fs-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-muted">
                                    Belum ada soal ujian
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Praktikum -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">Praktikum</h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="{{ route('instructor.exams.create-practicum', $course) }}" class="btn btn-sm btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Tambah Praktikum
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse($practicumExams as $exam)
                                <div class="d-flex align-items-center mb-5 p-4 border rounded {{ $exam->is_active ? 'border-success' : '' }}">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-gray-800 fs-6">{{ $exam->title }}</span>
                                            @if($exam->is_active)
                                                <span class="badge badge-success ms-2">Aktif</span>
                                            @endif
                                        </div>
                                        <div class="text-muted fs-7 mt-1">
                                            Nilai Minimum: {{ $exam->passing_score }}
                                        </div>
                                        @if($exam->file_path)
                                            <a href="{{ asset('storage/' . $exam->file_path) }}" target="_blank" class="text-primary fs-7">
                                                <i class="ki-duotone ki-file fs-6"></i> Lihat File
                                            </a>
                                        @endif
                                        @if($exam->external_link)
                                            <a href="{{ $exam->external_link }}" target="_blank" class="text-primary fs-7 ms-3">
                                                <i class="ki-duotone ki-external-link fs-6"></i> Link Eksternal
                                            </a>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('instructor.exams.toggle', ['type' => 'practicum', 'id' => $exam->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-{{ $exam->is_active ? 'warning' : 'success' }}" title="{{ $exam->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="ki-duotone ki-{{ $exam->is_active ? 'cross' : 'check' }} fs-2"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('instructor.exams.destroy', ['type' => 'practicum', 'id' => $exam->id]) }}" method="POST" onsubmit="return confirm('Hapus praktikum ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger">
                                                <i class="ki-duotone ki-trash fs-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-muted">
                                    Belum ada praktikum
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

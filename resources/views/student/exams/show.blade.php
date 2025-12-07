@extends('student.layouts.master')

@section('title', 'Ujian - ' . $course->title)

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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>{{ $course->title }} - Ujian Akhir</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('student.exams.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <div class="bg-light-primary rounded p-4 text-center">
                                <div class="fs-2x fw-bold text-primary">{{ $materialsProgress['completed'] }}/{{ $materialsProgress['total'] }}</div>
                                <div class="text-muted">Materi Selesai</div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if(!$canTakeExam)
                                <div class="alert alert-warning mb-0 h-100 d-flex align-items-center">
                                    <i class="ki-duotone ki-information fs-2x me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div>
                                        <strong>Anda belum bisa mengikuti ujian</strong><br>
                                        Selesaikan semua materi kursus terlebih dahulu untuk membuka akses ujian.
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-success mb-0 h-100 d-flex align-items-center">
                                    <i class="ki-duotone ki-check-circle fs-2x me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div>
                                        <strong>Anda sudah bisa mengikuti ujian</strong><br>
                                        Semua materi telah diselesaikan. Silakan kumpulkan jawaban ujian Anda.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 g-xl-10">
                <!-- Soal Ujian -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">
                                    <i class="ki-duotone ki-notepad fs-2 me-2 text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Soal Ujian
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($finalExam)
                                <h4 class="mb-3">{{ $finalExam->title }}</h4>
                                
                                @if($finalExam->description)
                                    <div class="bg-light rounded p-4 mb-4">
                                        {!! nl2br(e($finalExam->description)) !!}
                                    </div>
                                @endif

                                <div class="d-flex gap-2 mb-5">
                                    @if($finalExam->file_path)
                                        <a href="{{ asset('storage/' . $finalExam->file_path) }}" target="_blank" class="btn btn-light-primary">
                                            <i class="ki-duotone ki-file fs-2"></i>
                                            Download Soal
                                        </a>
                                    @endif
                                    @if($finalExam->external_link)
                                        <a href="{{ $finalExam->external_link }}" target="_blank" class="btn btn-light-info">
                                            <i class="ki-duotone ki-external-link fs-2"></i>
                                            Link Soal
                                        </a>
                                    @endif
                                </div>

                                <div class="separator my-5"></div>

                                @if($finalExamSubmission)
                                    <div class="alert alert-{{ $finalExamSubmission->status->value === 'passed' ? 'success' : ($finalExamSubmission->status->value === 'failed' ? 'danger' : 'info') }}">
                                        <strong>Status:</strong>
                                        @switch($finalExamSubmission->status->value)
                                            @case('pending')
                                                Menunggu penilaian
                                                @break
                                            @case('passed')
                                                Lulus (Nilai: {{ $finalExamSubmission->score }})
                                                @break
                                            @case('failed')
                                                Tidak Lulus (Nilai: {{ $finalExamSubmission->score }})
                                                @break
                                        @endswitch
                                        @if($finalExamSubmission->feedback)
                                            <br><small>Feedback: {{ $finalExamSubmission->feedback }}</small>
                                        @endif
                                    </div>
                                @elseif($canTakeExam)
                                    @if($finalExam->external_link && !$finalExam->file_path)
                                        {{-- Ujian via link eksternal - hanya perlu konfirmasi --}}
                                        <div class="alert alert-info mb-4">
                                            <i class="ki-duotone ki-information fs-4 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Kerjakan ujian melalui link di atas, lalu klik tombol konfirmasi di bawah.
                                        </div>
                                        <form action="{{ route('student.exams.submit-final', $course->slug) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="answer_text" value="Saya telah mengerjakan ujian melalui link eksternal pada {{ now()->format('d M Y H:i') }}">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="ki-duotone ki-check fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                Saya Telah Mengerjakan Ujian
                                            </button>
                                        </form>
                                    @else
                                        {{-- Ujian dengan upload file --}}
                                        <form action="{{ route('student.exams.submit-final', $course->slug) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="form-label">Upload Jawaban (PDF/Word/ZIP)</label>
                                                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.zip,.rar">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Atau tulis jawaban</label>
                                                <textarea name="answer_text" rows="4" class="form-control"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ki-duotone ki-send fs-2"></i>
                                                Kumpulkan Jawaban
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <div class="text-muted text-center py-5">
                                        Selesaikan materi untuk membuka form pengumpulan
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-10 text-muted">
                                    <i class="ki-duotone ki-notepad fs-5x mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <p>Belum ada soal ujian</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Praktikum -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">
                                    <i class="ki-duotone ki-code fs-2 me-2 text-info">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Praktikum
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($practicumExam)
                                <h4 class="mb-3">{{ $practicumExam->title }}</h4>
                                
                                @if($practicumExam->instructions)
                                    <div class="bg-light rounded p-4 mb-4">
                                        {!! nl2br(e($practicumExam->instructions)) !!}
                                    </div>
                                @endif

                                <div class="d-flex gap-2 mb-5">
                                    @if($practicumExam->file_path)
                                        <a href="{{ asset('storage/' . $practicumExam->file_path) }}" target="_blank" class="btn btn-light-primary">
                                            <i class="ki-duotone ki-file fs-2"></i>
                                            Download File
                                        </a>
                                    @endif
                                    @if($practicumExam->external_link)
                                        <a href="{{ $practicumExam->external_link }}" target="_blank" class="btn btn-light-info">
                                            <i class="ki-duotone ki-external-link fs-2"></i>
                                            Link Eksternal
                                        </a>
                                    @endif
                                </div>

                                <div class="separator my-5"></div>

                                @if($practicumSubmission)
                                    <div class="alert alert-{{ $practicumSubmission->status->value === 'passed' ? 'success' : ($practicumSubmission->status->value === 'failed' ? 'danger' : 'info') }}">
                                        <strong>Status:</strong>
                                        @switch($practicumSubmission->status->value)
                                            @case('pending')
                                                Menunggu penilaian
                                                @break
                                            @case('passed')
                                                Lulus (Nilai: {{ $practicumSubmission->score }})
                                                @break
                                            @case('failed')
                                                Tidak Lulus (Nilai: {{ $practicumSubmission->score }})
                                                @break
                                        @endswitch
                                        @if($practicumSubmission->feedback)
                                            <br><small>Feedback: {{ $practicumSubmission->feedback }}</small>
                                        @endif
                                    </div>
                                @elseif($canTakeExam)
                                    @if($practicumExam->external_link && !$practicumExam->file_path)
                                        {{-- Praktikum via link eksternal - hanya perlu konfirmasi --}}
                                        <div class="alert alert-info mb-4">
                                            <i class="ki-duotone ki-information fs-4 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Kerjakan praktikum melalui link di atas, lalu klik tombol konfirmasi di bawah.
                                        </div>
                                        <form action="{{ route('student.exams.submit-practicum', $course->slug) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="confirm_external" value="1">
                                            <input type="hidden" name="answer_text" value="Saya telah mengerjakan praktikum melalui link eksternal pada {{ now()->format('d M Y H:i') }}">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="ki-duotone ki-check fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                Saya Telah Mengerjakan Praktikum
                                            </button>
                                        </form>
                                    @else
                                        {{-- Praktikum dengan upload file --}}
                                        <form action="{{ route('student.exams.submit-practicum', $course->slug) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="form-label required">Upload File Praktikum</label>
                                                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.zip,.rar" required>
                                                <div class="form-text">Maksimal 20MB (PDF/Word/ZIP)</div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Catatan tambahan</label>
                                                <textarea name="answer_text" rows="3" class="form-control"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info w-100">
                                                <i class="ki-duotone ki-send fs-2"></i>
                                                Kumpulkan Praktikum
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <div class="text-muted text-center py-5">
                                        Selesaikan materi untuk membuka form pengumpulan
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-10 text-muted">
                                    <i class="ki-duotone ki-code fs-5x mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <p>Belum ada praktikum</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

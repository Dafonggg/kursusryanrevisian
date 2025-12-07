@extends('admin.layouts.master')

@section('title', 'Detail Hasil Ujian')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Detail Hasil Ujian</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('admin.exam-results.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-xl-8">
                            <!-- Info Student -->
                            <div class="mb-8">
                                <h4 class="mb-4">Informasi Student</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Nama</label>
                                        <div class="fw-bold fs-5">{{ $submission->enrollment->user->name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <div class="fw-bold fs-5">{{ $submission->enrollment->user->email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Kursus</label>
                                        <div class="fw-bold fs-5">{{ $submission->enrollment->course->title }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Tipe Ujian</label>
                                        <div>
                                            <span class="badge badge-light-{{ $submission->exam_type === 'final_exam' ? 'primary' : 'info' }} fs-6">
                                                {{ $submission->exam_type === 'final_exam' ? 'Ujian Teori' : 'Praktikum' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="separator my-5"></div>

                            <!-- Info Soal -->
                            <div class="mb-8">
                                <h4 class="mb-4">Soal: {{ $exam->title }}</h4>
                                
                                @if($exam->description ?? $exam->instructions)
                                    <div class="bg-light rounded p-4 mb-4">
                                        {!! nl2br(e($exam->description ?? $exam->instructions)) !!}
                                    </div>
                                @endif

                                <div class="d-flex gap-2">
                                    @if($exam->file_path)
                                        <a href="{{ asset('storage/' . $exam->file_path) }}" target="_blank" class="btn btn-sm btn-light-primary">
                                            <i class="ki-duotone ki-file fs-2"></i> File Soal
                                        </a>
                                    @endif
                                    @if($exam->external_link)
                                        <a href="{{ $exam->external_link }}" target="_blank" class="btn btn-sm btn-light-info">
                                            <i class="ki-duotone ki-external-link fs-2"></i> Link Soal
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="separator my-5"></div>

                            <!-- Jawaban -->
                            <div class="mb-8">
                                <h4 class="mb-4">Jawaban Student</h4>
                                
                                @if($submission->file_path)
                                    <div class="mb-4">
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-primary">
                                            <i class="ki-duotone ki-file-down fs-2"></i> Download Jawaban
                                        </a>
                                    </div>
                                @endif

                                @if($submission->answer_text)
                                    <div class="bg-light rounded p-4">
                                        {!! nl2br(e($submission->answer_text)) !!}
                                    </div>
                                @endif

                                @if(!$submission->file_path && !$submission->answer_text)
                                    <p class="text-muted">Tidak ada jawaban</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h4 class="mb-5">Hasil Penilaian</h4>
                                    
                                    <div class="mb-5">
                                        <label class="form-label text-muted">Status</label>
                                        <div>
                                            @switch($submission->status->value)
                                                @case('pending')
                                                    <span class="badge badge-warning fs-5">Menunggu Penilaian</span>
                                                    @break
                                                @case('passed')
                                                    <span class="badge badge-success fs-5">Lulus</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge badge-danger fs-5">Tidak Lulus</span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>

                                    @if($submission->score !== null)
                                        <div class="mb-5">
                                            <label class="form-label text-muted">Nilai</label>
                                            <div class="display-5 fw-bold {{ $submission->status->value === 'passed' ? 'text-success' : 'text-danger' }}">
                                                {{ $submission->score }}
                                            </div>
                                            <div class="text-muted fs-7">Nilai minimum: {{ $exam->passing_score }}</div>
                                        </div>
                                    @endif

                                    @if($submission->feedback)
                                        <div class="mb-5">
                                            <label class="form-label text-muted">Feedback</label>
                                            <div class="bg-white rounded p-3">
                                                {!! nl2br(e($submission->feedback)) !!}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="separator my-5"></div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Waktu Submit</label>
                                        <div class="fw-bold">{{ $submission->submitted_at->format('d M Y H:i') }}</div>
                                    </div>

                                    @if($submission->graded_at)
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Waktu Dinilai</label>
                                            <div class="fw-bold">{{ $submission->graded_at->format('d M Y H:i') }}</div>
                                        </div>
                                    @endif

                                    @if($submission->grader)
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Dinilai Oleh</label>
                                            <div class="fw-bold">{{ $submission->grader->name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

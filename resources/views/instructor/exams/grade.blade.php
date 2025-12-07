@extends('instructor.layouts.master')

@section('title', 'Penilaian Jawaban')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-5 g-xl-10">
                <div class="col-xl-8">
                    <div class="card mb-5">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h2>Detail Jawaban</h2>
                            </div>
                            <div class="card-toolbar">
                                <a href="{{ route('instructor.exams.submissions') }}" class="btn btn-light-primary">
                                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Student</label>
                                    <div class="fw-bold fs-5">{{ $submission->enrollment->user->name }}</div>
                                    <div class="text-muted">{{ $submission->enrollment->user->email }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Kursus</label>
                                    <div class="fw-bold fs-5">{{ $submission->enrollment->course->title }}</div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Tipe Ujian</label>
                                    <div>
                                        <span class="badge badge-light-{{ $submission->exam_type === 'final_exam' ? 'primary' : 'info' }} fs-7">
                                            {{ $submission->exam_type === 'final_exam' ? 'Ujian Teori' : 'Praktikum' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Waktu Submit</label>
                                    <div class="fw-bold">{{ $submission->submitted_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>

                            <div class="separator my-5"></div>

                            <h4 class="mb-4">Soal: {{ $exam->title }}</h4>
                            
                            @if($exam->description ?? $exam->instructions)
                                <div class="bg-light-primary rounded p-4 mb-5">
                                    <label class="form-label text-muted">Instruksi Soal</label>
                                    <div>{!! nl2br(e($exam->description ?? $exam->instructions)) !!}</div>
                                </div>
                            @endif

                            @if($exam->file_path)
                                <div class="mb-3">
                                    <a href="{{ asset('storage/' . $exam->file_path) }}" target="_blank" class="btn btn-sm btn-light-primary">
                                        <i class="ki-duotone ki-file fs-2"></i>
                                        Lihat File Soal
                                    </a>
                                </div>
                            @endif

                            @if($exam->external_link)
                                <div class="mb-3">
                                    <a href="{{ $exam->external_link }}" target="_blank" class="btn btn-sm btn-light-info">
                                        <i class="ki-duotone ki-external-link fs-2"></i>
                                        Link Soal Eksternal
                                    </a>
                                </div>
                            @endif

                            <div class="separator my-5"></div>

                            <h4 class="mb-4">Jawaban Student</h4>

                            @if($submission->file_path)
                                <div class="mb-4">
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-primary">
                                        <i class="ki-duotone ki-file-down fs-2"></i>
                                        Download Jawaban
                                    </a>
                                </div>
                            @endif

                            @if($submission->answer_text)
                                <div class="bg-light rounded p-4">
                                    <label class="form-label text-muted">Jawaban Text</label>
                                    <div>{!! nl2br(e($submission->answer_text)) !!}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card sticky-top" style="top: 80px;">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3>Penilaian</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($submission->status->value === 'pending')
                                <form action="{{ route('instructor.exams.grade', $submission) }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-5">
                                        <label class="form-label required">Nilai (0-100)</label>
                                        <input type="number" name="score" class="form-control form-control-lg @error('score') is-invalid @enderror" min="0" max="100" required>
                                        <div class="form-text">Nilai minimum lulus: {{ $exam->passing_score }}</div>
                                        @error('score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label">Feedback / Komentar</label>
                                        <textarea name="feedback" rows="4" class="form-control @error('feedback') is-invalid @enderror"></textarea>
                                        @error('feedback')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ki-duotone ki-check fs-2"></i>
                                        Simpan Nilai
                                    </button>
                                </form>
                            @else
                                <div class="mb-5">
                                    <label class="form-label text-muted">Status</label>
                                    <div>
                                        @switch($submission->status->value)
                                            @case('passed')
                                                <span class="badge badge-success fs-5">Lulus</span>
                                                @break
                                            @case('failed')
                                                <span class="badge badge-danger fs-5">Tidak Lulus</span>
                                                @break
                                            @default
                                                <span class="badge badge-info fs-5">Dinilai</span>
                                        @endswitch
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label text-muted">Nilai</label>
                                    <div class="display-6 fw-bold {{ $submission->score >= $exam->passing_score ? 'text-success' : 'text-danger' }}">
                                        {{ $submission->score }}
                                    </div>
                                    <div class="text-muted">Minimum: {{ $exam->passing_score }}</div>
                                </div>

                                @if($submission->feedback)
                                    <div class="mb-5">
                                        <label class="form-label text-muted">Feedback</label>
                                        <div class="bg-light rounded p-3">
                                            {!! nl2br(e($submission->feedback)) !!}
                                        </div>
                                    </div>
                                @endif

                                @if($submission->graded_at)
                                    <div class="text-muted fs-7">
                                        Dinilai pada: {{ $submission->graded_at->format('d M Y H:i') }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

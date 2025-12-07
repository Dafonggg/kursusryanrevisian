@extends('student.layouts.master')

@section('title', 'Hasil Ujian')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Hasil Ujian Saya</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('student.exams.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($submissions->isEmpty())
                        <div class="text-center py-10">
                            <div class="mb-5">
                                <i class="ki-duotone ki-chart fs-5x text-muted">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <p class="text-muted fs-5">Anda belum mengumpulkan jawaban ujian apapun</p>
                            <a href="{{ route('student.exams.index') }}" class="btn btn-primary">
                                Lihat Ujian yang Tersedia
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>Kursus</th>
                                        <th>Tipe Ujian</th>
                                        <th>Waktu Submit</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Status</th>
                                        <th>Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                    <tr>
                                        <td>
                                            <span class="text-gray-800 fw-bold">{{ $submission->enrollment->course->title }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $submission->exam_type === 'final_exam' ? 'primary' : 'info' }}">
                                                {{ $submission->exam_type === 'final_exam' ? 'Ujian Teori' : 'Praktikum' }}
                                            </span>
                                        </td>
                                        <td>{{ $submission->submitted_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            @if($submission->score !== null)
                                                <span class="fs-4 fw-bold {{ $submission->status->value === 'passed' ? 'text-success' : 'text-danger' }}">
                                                    {{ $submission->score }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @switch($submission->status->value)
                                                @case('pending')
                                                    <span class="badge badge-light-warning">Menunggu Penilaian</span>
                                                    @break
                                                @case('passed')
                                                    <span class="badge badge-light-success">Lulus</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge badge-light-danger">Tidak Lulus</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light-info">Dinilai</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($submission->feedback)
                                                <span class="text-muted" title="{{ $submission->feedback }}">
                                                    {{ Str::limit($submission->feedback, 50) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

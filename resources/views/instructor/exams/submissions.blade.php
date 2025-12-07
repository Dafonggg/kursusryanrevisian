@extends('instructor.layouts.master')

@section('title', 'Jawaban Student')

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

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Jawaban Student</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('instructor.exams.overview') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($submissions->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-muted">Belum ada jawaban yang dikumpulkan</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>Student</th>
                                        <th>Kursus</th>
                                        <th>Tipe</th>
                                        <th>Waktu Submit</th>
                                        <th>Status</th>
                                        <th>Nilai</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                    <tr>
                                        <td>
                                            <span class="text-gray-800 fw-bold">{{ $submission->enrollment->user->name }}</span>
                                            <br>
                                            <span class="text-muted fs-7">{{ $submission->enrollment->user->email }}</span>
                                        </td>
                                        <td>{{ $submission->enrollment->course->title }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $submission->exam_type === 'final_exam' ? 'primary' : 'info' }}">
                                                {{ $submission->exam_type === 'final_exam' ? 'Ujian' : 'Praktikum' }}
                                            </span>
                                        </td>
                                        <td>{{ $submission->submitted_at->format('d M Y H:i') }}</td>
                                        <td>
                                            @switch($submission->status->value)
                                                @case('pending')
                                                    <span class="badge badge-light-warning">Menunggu</span>
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
                                            @if($submission->score !== null)
                                                <span class="fw-bold {{ $submission->score >= 60 ? 'text-success' : 'text-danger' }}">
                                                    {{ $submission->score }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('instructor.exams.show-submission', $submission) }}" class="btn btn-sm btn-light-primary">
                                                {{ $submission->status->value === 'pending' ? 'Nilai' : 'Lihat' }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-5">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

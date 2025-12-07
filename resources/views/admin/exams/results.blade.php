@extends('admin.layouts.master')

@section('title', 'Hasil Ujian Student')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- Stats -->
            <div class="row g-5 g-xl-10 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush hoverable card-xl-stretch mb-xl-8">
                        <div class="card-body">
                            <span class="badge badge-primary mb-2">Total</span>
                            <div class="text-gray-900 fw-bold fs-2">{{ $stats['total'] }}</div>
                            <div class="text-gray-500">Total Submission</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush hoverable card-xl-stretch mb-xl-8">
                        <div class="card-body">
                            <span class="badge badge-warning mb-2">Pending</span>
                            <div class="text-gray-900 fw-bold fs-2">{{ $stats['pending'] }}</div>
                            <div class="text-gray-500">Menunggu Penilaian</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush hoverable card-xl-stretch mb-xl-8">
                        <div class="card-body">
                            <span class="badge badge-success mb-2">Lulus</span>
                            <div class="text-gray-900 fw-bold fs-2">{{ $stats['passed'] }}</div>
                            <div class="text-gray-500">Lulus</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush hoverable card-xl-stretch mb-xl-8">
                        <div class="card-body">
                            <span class="badge badge-danger mb-2">Tidak Lulus</span>
                            <div class="text-gray-900 fw-bold fs-2">{{ $stats['failed'] }}</div>
                            <div class="text-gray-500">Tidak Lulus</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Hasil Ujian Student</h2>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter -->
                    <form method="GET" class="mb-5">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama/email..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="course" class="form-select">
                                    <option value="">Semua Kursus</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->slug }}" {{ request('course') === $course->slug ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-select">
                                    <option value="">Semua Tipe</option>
                                    <option value="final_exam" {{ request('type') === 'final_exam' ? 'selected' : '' }}>Ujian</option>
                                    <option value="practicum" {{ request('type') === 'practicum' ? 'selected' : '' }}>Praktikum</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Lulus</option>
                                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Tidak Lulus</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>

                    @if($submissions->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-muted">Tidak ada data hasil ujian</p>
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
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                    <tr>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block">{{ $submission->enrollment->user->name }}</span>
                                            <span class="text-muted fs-7">{{ $submission->enrollment->user->email }}</span>
                                        </td>
                                        <td>{{ $submission->enrollment->course->title }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $submission->exam_type === 'final_exam' ? 'primary' : 'info' }}">
                                                {{ $submission->exam_type === 'final_exam' ? 'Ujian' : 'Praktikum' }}
                                            </span>
                                        </td>
                                        <td>{{ $submission->submitted_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            @if($submission->score !== null)
                                                <span class="fw-bold {{ $submission->status->value === 'passed' ? 'text-success' : 'text-danger' }}">
                                                    {{ $submission->score }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
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
                                            @endswitch
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.exam-results.show', $submission) }}" class="btn btn-sm btn-light-primary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-5">
                            {{ $submissions->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('instructor.layouts.master')

@section('title', 'Ujian Akhir')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h2>Ujian Akhir</h2>
                            </div>
                            <div class="card-toolbar">
                                @if($pendingSubmissions > 0)
                                    <a href="{{ route('instructor.exams.submissions') }}" class="btn btn-warning">
                                        <i class="ki-duotone ki-notification-on fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        {{ $pendingSubmissions }} Jawaban Menunggu Penilaian
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body py-4">
                            @if($courses->isEmpty())
                                <div class="text-center py-10">
                                    <p class="text-muted">Anda belum memiliki kursus</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th>Kursus</th>
                                                <th class="text-center">Soal Ujian</th>
                                                <th class="text-center">Praktikum</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($courses as $course)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-5">
                                                            @if($course->image)
                                                                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="rounded">
                                                            @else
                                                                <div class="symbol-label bg-light-primary">
                                                                    <i class="ki-duotone ki-book fs-2x text-primary">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-800 fw-bold d-block fs-6">{{ $course->title }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-light-{{ $course->final_exams_count > 0 ? 'success' : 'warning' }}">
                                                        {{ $course->final_exams_count }} soal
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-light-{{ $course->practicum_exams_count > 0 ? 'success' : 'warning' }}">
                                                        {{ $course->practicum_exams_count }} praktikum
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('instructor.exams.index', $course) }}" class="btn btn-sm btn-light-primary">
                                                        Kelola Ujian
                                                    </a>
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
    </div>
</div>
@endsection

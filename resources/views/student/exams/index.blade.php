@extends('student.layouts.master')

@section('title', 'Ujian Akhir')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Ujian Akhir</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('student.exams.results') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-chart fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Lihat Hasil Ujian
                        </a>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($enrollments->isEmpty())
                        <div class="text-center py-10">
                            <div class="mb-5">
                                <i class="ki-duotone ki-notepad fs-5x text-muted">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                            <p class="text-muted fs-5">Belum ada ujian yang tersedia untuk kursus Anda</p>
                        </div>
                    @else
                        <div class="row g-5">
                            @foreach($enrollments as $enrollment)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border border-gray-300 h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="symbol symbol-50px me-4">
                                                    @if($enrollment->course->image)
                                                        <img src="{{ asset('storage/' . $enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="rounded">
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
                                                    <h4 class="mb-1">{{ $enrollment->course->title }}</h4>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-wrap gap-2 mb-4">
                                                @if($enrollment->course->activeFinalExam)
                                                    <span class="badge badge-light-primary">Ujian Tersedia</span>
                                                @endif
                                                @if($enrollment->course->activePracticumExam)
                                                    <span class="badge badge-light-info">Praktikum Tersedia</span>
                                                @endif
                                            </div>

                                            @php
                                                $canTake = $enrollment->canTakeExam();
                                                $progress = $enrollment->getTotalMaterialsCount() > 0 
                                                    ? round(($enrollment->getCompletedMaterialsCount() / $enrollment->getTotalMaterialsCount()) * 100) 
                                                    : 0;
                                            @endphp

                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted fs-7">Progress Materi</span>
                                                    <span class="fw-bold">{{ $progress }}%</span>
                                                </div>
                                                <div class="progress h-6px">
                                                    <div class="progress-bar bg-{{ $progress == 100 ? 'success' : 'primary' }}" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>

                                            @if(!$canTake)
                                                <div class="alert alert-warning py-2 px-3 mb-4">
                                                    <i class="ki-duotone ki-information fs-5 me-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    Selesaikan semua materi untuk mengikuti ujian
                                                </div>
                                            @endif

                                            <a href="{{ route('student.exams.show', $enrollment->course->slug) }}" class="btn btn-{{ $canTake ? 'primary' : 'secondary' }} w-100">
                                                {{ $canTake ? 'Ikuti Ujian' : 'Lihat Detail' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

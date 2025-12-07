@extends('student.layouts.master')

@section('title', $announcement->title)

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <a href="{{ route('student.announcements.index') }}" class="btn btn-light-primary btn-sm me-3">
                            <i class="ki-duotone ki-arrow-left fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Kembali
                        </a>
                        <h2>Pengumuman</h2>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="mb-5">
                        <h1 class="fs-2 fw-bold text-gray-900 mb-3">{{ $announcement->title }}</h1>
                        <div class="d-flex gap-3 align-items-center mb-5">
                            @if($announcement->isGlobal())
                                <span class="badge badge-light-primary fs-7">Pengumuman Global</span>
                            @else
                                <span class="badge badge-light-info fs-7">{{ $announcement->course->title }}</span>
                            @endif
                            <span class="text-muted fs-7">
                                <i class="ki-duotone ki-calendar fs-6 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ $announcement->published_at?->format('d M Y H:i') ?? $announcement->created_at->format('d M Y H:i') }}
                            </span>
                            <span class="text-muted fs-7">
                                <i class="ki-duotone ki-user fs-6 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ $announcement->creator->name ?? 'Admin' }}
                            </span>
                        </div>
                    </div>

                    <div class="separator separator-dashed mb-5"></div>

                    <div class="text-gray-800 fs-5" style="line-height: 1.8;">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

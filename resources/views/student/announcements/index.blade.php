@extends('student.layouts.master')

@section('title', 'Pengumuman')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Pengumuman</h2>
                    </div>
                    <div class="card-toolbar">
                        <form action="{{ route('student.announcements.index') }}" method="GET">
                            <select name="course" class="form-select form-select-sm w-200px" onchange="this.form.submit()">
                                <option value="">Semua Pengumuman</option>
                                <option value="global" {{ request('course') === 'global' ? 'selected' : '' }}>Pengumuman Global</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($announcements->isEmpty())
                        <div class="text-center py-10">
                            <div class="mb-5">
                                <i class="ki-duotone ki-notification fs-5x text-muted">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                            <p class="text-muted fs-5">Belum ada pengumuman</p>
                        </div>
                    @else
                        <div class="row g-5">
                            @foreach($announcements as $announcement)
                                <div class="col-12">
                                    <div class="card border border-gray-300 hover-elevate-up">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h4 class="mb-1">{{ $announcement->title }}</h4>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        @if($announcement->isGlobal())
                                                            <span class="badge badge-light-primary">Global</span>
                                                        @else
                                                            <span class="badge badge-light-info">{{ $announcement->course->title }}</span>
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
                                            </div>
                                            <div class="text-gray-700 mb-3">
                                                {!! nl2br(e(Str::limit($announcement->content, 300))) !!}
                                            </div>
                                            @if(strlen($announcement->content) > 300)
                                                <a href="{{ route('student.announcements.show', $announcement) }}" class="btn btn-sm btn-light-primary">
                                                    Baca Selengkapnya
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5">
                            {{ $announcements->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('instructor.layouts.master')

@section('title', 'Pengumuman')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h2>Pengumuman Kursus</h2>
                            </div>
                            <div class="card-toolbar gap-3">
                                <form action="{{ route('instructor.announcements.index') }}" method="GET">
                                    <select name="course" class="form-select form-select-sm w-200px" onchange="this.form.submit()">
                                        <option value="">Semua Kursus</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <a href="{{ route('instructor.announcements.create') }}" class="btn btn-primary btn-sm">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Buat Pengumuman
                                </a>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($announcements->isEmpty())
                                <div class="text-center py-10">
                                    <p class="text-muted">Belum ada pengumuman</p>
                                    <a href="{{ route('instructor.announcements.create') }}" class="btn btn-primary mt-3">Buat Pengumuman Pertama</a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th>Judul</th>
                                                <th>Kursus</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($announcements as $announcement)
                                            <tr>
                                                <td>
                                                    <span class="text-gray-800 fw-bold">{{ $announcement->title }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-600">{{ $announcement->course->title ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    @if($announcement->is_active)
                                                        <span class="badge badge-light-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Nonaktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $announcement->published_at?->format('d M Y H:i') ?? $announcement->created_at->format('d M Y H:i') }}</td>
                                                <td class="text-end">
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <a href="{{ route('instructor.announcements.edit', $announcement) }}" class="btn btn-sm btn-icon btn-light-primary" title="Edit">
                                                            <i class="ki-duotone ki-pencil fs-5">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </a>
                                                        <form action="{{ route('instructor.announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus">
                                                                <i class="ki-duotone ki-trash fs-5">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    </div>
</div>
@endsection

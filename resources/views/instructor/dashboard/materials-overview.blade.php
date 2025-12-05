@extends('instructor.layouts.master')

@section('title', 'Kelola Materi Kursus - Instructor')
@section('description', 'Kelola materi untuk kursus Anda')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Kelola Materi Kursus</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Materi Kursus</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('instructor.courses') }}" class="btn btn-sm fw-bold btn-secondary">Kembali ke Kursus</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Kursus Saya</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Pilih kursus untuk mengelola materinya</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>Kursus</th>
						<th>Jumlah Materi</th>
						<th>Mode</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($courses as $course)
						<tr>
							<td>
								<div class="d-flex align-items-center">
									@if($course->image)
										<div class="symbol symbol-40px me-3">
											<img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-100" />
										</div>
									@endif
									<div class="d-flex flex-column">
										<span class="text-gray-900 fw-bold">{{ $course->title }}</span>
										<span class="text-gray-500 fs-7">{{ \Illuminate\Support\Str::limit($course->description, 50) }}</span>
									</div>
								</div>
							</td>
							<td>
								<span class="badge badge-light-primary">{{ $course->materials_count }} materi</span>
							</td>
							<td>
								<span class="badge badge-{{ $course->mode->value == 'online' ? 'success' : ($course->mode->value == 'offline' ? 'primary' : 'info') }}">
									{{ ucfirst($course->mode->value) }}
								</span>
							</td>
							<td>
								<a href="{{ route('instructor.materials.index', $course->slug) }}" class="btn btn-sm btn-primary">
									<i class="ki-duotone ki-file fs-5 me-1">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									Kelola Materi
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center text-muted py-10">
								<div class="d-flex flex-column align-items-center">
									<i class="ki-duotone ki-file fs-3x text-gray-400 mb-3">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<span>Belum ada kursus</span>
								</div>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection


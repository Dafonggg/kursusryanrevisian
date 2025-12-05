@extends('student.layouts.master')

@section('title', $material->title . ' - ' . $course->title . ' | Kursus Ryan Komputer')
@section('description', 'Detail materi kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">{{ $material->title }}</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.materials', $course->slug) }}" class="text-muted text-hover-primary">{{ $course->title }}</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">{{ $material->title }}</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('student.materials', $course->slug) }}" class="btn btn-sm fw-bold btn-secondary">Kembali ke Daftar Materi</a>
		</div>
	</div>
</div>
@endsection

@section('content')
@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		{{ session('error') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
@endif

<div class="row g-5 g-xl-10">
	<div class="col-xl-8">
		<div class="card card-flush mb-5">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">{{ $material->title }}</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Kursus: {{ $course->title }}</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				@if($material->url)
					@php
						// Cek apakah URL adalah YouTube
						$isYouTube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $material->url, $matches);
						$youtubeId = $isYouTube ? $matches[1] : null;
					@endphp
					
					@if($youtubeId)
						<div class="mb-10">
							<div class="ratio ratio-16x9">
								<iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
						</div>
					@else
						<div class="mb-10">
							<a href="{{ $material->url }}" target="_blank" class="btn btn-primary btn-lg">
								<i class="ki-duotone ki-external-link fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Buka Link Materi
							</a>
						</div>
					@endif
				@endif

				@if($material->path)
					<div class="mb-10">
						<iframe src="{{ asset('storage/' . $material->path) }}" class="w-100" style="height: 600px;" frameborder="0"></iframe>
						<div class="mt-3">
							<a href="{{ asset('storage/' . $material->path) }}" target="_blank" class="btn btn-info" download>
								<i class="ki-duotone ki-file-down fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Download File
							</a>
						</div>
					</div>
				@endif

				@if(!$material->url && !$material->path)
					<div class="alert alert-warning">
						Konten materi belum tersedia.
					</div>
				@endif
			</div>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Aksi</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				@if($isCompleted)
					<div class="alert alert-success">
						<i class="ki-duotone ki-check-circle fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Materi ini sudah Anda selesaikan.
					</div>
				@else
					<form action="{{ route('student.materials.complete', [$course->slug, $material->id]) }}" method="POST">
						@csrf
						<button type="submit" class="btn btn-primary btn-lg w-100" onclick="return confirm('Apakah Anda yakin sudah menyelesaikan materi ini?')">
							<i class="ki-duotone ki-check fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Selesai Akses
						</button>
					</form>
				@endif

				@if($nextMaterial)
					<div class="mt-5">
						<a href="{{ route('student.materials.show', [$course->slug, $nextMaterial->id]) }}" class="btn btn-light-primary w-100">
							Materi Berikutnya: {{ $nextMaterial->title }}
							<i class="ki-duotone ki-arrow-right fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection


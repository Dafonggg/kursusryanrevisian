@extends('admin.layouts.master')

@section('title', 'Tambah Materi - Admin')
@section('description', 'Tambah materi kursus baru')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Tambah Materi</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.courses.index') }}" class="text-muted text-hover-primary">Kursus</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Tambah Materi</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.materials.index', $course->slug) }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Form Tambah Materi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Kursus: {{ $course->title }}</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<form action="{{ route('admin.materials.store', $course->slug) }}" method="POST" enctype="multipart/form-data">
			@csrf
			
			@if(session('success'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					{{ session('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			@if($errors->any())
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<ul class="mb-0">
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			<div class="mb-10">
				<label class="form-label required">Judul Materi</label>
				<input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
					value="{{ old('title') }}" placeholder="Masukkan judul materi" required>
				@error('title')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label">Urutan</label>
				<input type="number" name="order" class="form-control @error('order') is-invalid @enderror" 
					value="{{ old('order') }}" placeholder="Otomatis jika kosong" min="0">
				<div class="form-text">Kosongkan untuk otomatis di akhir</div>
				@error('order')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label">Upload File</label>
				<input type="file" name="path" id="material_file" class="form-control @error('path') is-invalid @enderror" 
					accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
				<div class="form-text">Format: PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (Maks: 10MB). Bisa dikombinasikan dengan URL.</div>
				@error('path')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-10">
				<label class="form-label">URL (YouTube, Drive, dll)</label>
				<input type="url" name="url" id="material_url" class="form-control @error('url') is-invalid @enderror" 
					value="{{ old('url') }}" placeholder="https://youtube.com/watch?v=...">
				<div class="form-text">Masukkan link video atau dokumen online. Bisa dikombinasikan dengan file upload. Minimal salah satu harus diisi.</div>
				@error('url')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="d-flex justify-content-end gap-2">
				<a href="{{ route('admin.materials.index', $course->slug) }}" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan Materi</button>
			</div>
		</form>
	</div>
</div>

@endsection


@extends('admin.layouts.master')

@section('title', 'Edit Pengumuman - Admin')
@section('description', 'Edit pengumuman')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Pengumuman</h1>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.announcements.index') }}" class="btn btn-sm fw-bold btn-light">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-body">
		<form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
			@csrf
			@method('PUT')
			
			<div class="mb-5">
				<label class="form-label required">Judul Pengumuman</label>
				<input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title) }}" required>
				@error('title')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-5">
				<label class="form-label">Kursus (Kosongkan untuk Pengumuman Global)</label>
				<select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
					<option value="">-- Pengumuman Global --</option>
					@foreach($courses as $course)
						<option value="{{ $course->id }}" {{ old('course_id', $announcement->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
					@endforeach
				</select>
				@error('course_id')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="mb-5">
				<label class="form-label required">Isi Pengumuman</label>
				<textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
				@error('content')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="row mb-5">
				<div class="col-md-6">
					<label class="form-label">Tanggal Publikasi</label>
					<input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', $announcement->published_at?->format('Y-m-d\TH:i')) }}">
					@error('published_at')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="col-md-6">
					<label class="form-label">Status</label>
					<div class="form-check form-switch mt-2">
						<input type="hidden" name="is_active" value="0">
						<input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
						<label class="form-check-label">Aktif</label>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-end">
				<button type="submit" class="btn btn-primary">Perbarui Pengumuman</button>
			</div>
		</form>
	</div>
</div>
@endsection

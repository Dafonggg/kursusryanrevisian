@extends('instructor.layouts.master')

@section('title', 'Buat Pengumuman')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h2>Buat Pengumuman Baru</h2>
                            </div>
                            <div class="card-toolbar">
                                <a href="{{ route('instructor.announcements.index') }}" class="btn btn-light btn-sm">Kembali</a>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <form action="{{ route('instructor.announcements.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-5">
                                    <label class="form-label required">Judul Pengumuman</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="form-label required">Kursus</label>
                                    <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                        <option value="">Pilih Kursus</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="form-label required">Isi Pengumuman</label>
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label">Aktifkan pengumuman</label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

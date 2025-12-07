@extends('instructor.layouts.master')

@section('title', 'Tambah Praktikum - ' . $course->title)

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Tambah Praktikum - {{ $course->title }}</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('instructor.exams.index', $course) }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('instructor.exams.store-practicum', $course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="form-label required">Judul Praktikum</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Instruksi Praktikum</label>
                            <textarea name="instructions" rows="6" class="form-control @error('instructions') is-invalid @enderror">{{ old('instructions') }}</textarea>
                            <div class="form-text">Jelaskan apa yang harus dikerjakan oleh student</div>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label">Upload File Pendukung (PDF/Word/ZIP)</label>
                                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.zip,.rar">
                                <div class="form-text">Maksimal 10MB</div>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Link Eksternal</label>
                                <input type="url" name="external_link" class="form-control @error('external_link') is-invalid @enderror" value="{{ old('external_link') }}" placeholder="https://...">
                                @error('external_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Nilai Minimum Lulus</label>
                                <input type="number" name="passing_score" class="form-control @error('passing_score') is-invalid @enderror" value="{{ old('passing_score', 60) }}" min="0" max="100" required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktifkan praktikum ini</label>
                                </div>
                                <div class="form-text">Jika diaktifkan, praktikum sebelumnya akan dinonaktifkan</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-duotone ki-check fs-2"></i>
                                Simpan Praktikum
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

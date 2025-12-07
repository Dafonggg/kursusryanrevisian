@extends('admin.layouts.master')

@section('title', 'Sertifikat - Admin')
@section('description', 'Daftar semua sertifikat')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Sertifikat</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Sertifikat</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Sertifikat</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Kelola sertifikat peserta kursus</span>
		</h3>
	</div>
	<div class="card-body pt-0">
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

		<!-- Filter Form -->
		<div class="card mb-5">
			<div class="card-body">
				<form method="GET" action="{{ route('admin.certificates.index') }}" class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="">Semua Status</option>
							<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
							<option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
							<option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
						</select>
					</div>
					<div class="col-md-2 d-flex align-items-end">
						<button type="submit" class="btn btn-primary w-100">Filter</button>
					</div>
				</form>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
				<thead>
					<tr class="fw-bold text-muted">
						<th>No. Sertifikat</th>
						<th>Siswa</th>
						<th>Kursus</th>
						<th class="text-center">Nilai Ujian</th>
						<th class="text-center">Nilai Praktikum</th>
						<th>Tanggal Terbit</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($certificates as $certificate)
						@php
							$finalSubmission = $certificate->enrollment->getFinalExamSubmission();
							$practicumSubmission = $certificate->enrollment->getPracticumSubmission();
							$canGenerate = $certificate->enrollment->canGetCertificate();
						@endphp
						<tr>
							<td class="text-gray-900 fw-bold">{{ $certificate->certificate_no }}</td>
							<td>
								@if($certificate->enrollment->user)
									<span class="text-gray-900 fw-semibold">{{ $certificate->enrollment->user->name }}</span>
									<br>
									<span class="text-gray-500 fs-7">{{ $certificate->enrollment->user->email }}</span>
								@else
									<span class="text-gray-500 fw-semibold fst-italic">User Dihapus</span>
								@endif
							</td>
							<td class="text-gray-900">{{ $certificate->enrollment->course->title }}</td>
							<td class="text-center">
								@if($finalSubmission && $finalSubmission->score !== null)
									<span class="badge badge-light-{{ $finalSubmission->status->value === 'passed' ? 'success' : 'danger' }} fs-6">
										{{ $finalSubmission->score }}
									</span>
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
							<td class="text-center">
								@if($practicumSubmission && $practicumSubmission->score !== null)
									<span class="badge badge-light-{{ $practicumSubmission->status->value === 'passed' ? 'success' : 'danger' }} fs-6">
										{{ $practicumSubmission->score }}
									</span>
								@else
									<span class="text-muted">-</span>
								@endif
							</td>
							<td class="text-gray-700">{{ $certificate->issued_at->format('d/m/Y') }}</td>
							<td>
								@if($certificate->status->value === 'pending')
									<span class="badge badge-light-warning">Pending</span>
								@elseif($certificate->status->value === 'approved')
									<span class="badge badge-light-success">Approved</span>
								@else
									<span class="badge badge-light-danger">Rejected</span>
								@endif
							</td>
							<td>
								<div class="d-flex gap-2">
									@if($certificate->status->value === 'pending')
										<form action="{{ route('admin.certificates.approve', $certificate->id) }}" method="POST" class="d-inline">
											@csrf
											<button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve sertifikat ini?')">
												<i class="ki-duotone ki-check fs-5">
													<span class="path1"></span>
													<span class="path2"></span>
												</i>
											</button>
										</form>
										<form action="{{ route('admin.certificates.reject', $certificate->id) }}" method="POST" class="d-inline">
											@csrf
											<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak sertifikat ini?')">
												<i class="ki-duotone ki-cross fs-5">
													<span class="path1"></span>
													<span class="path2"></span>
												</i>
											</button>
										</form>
									@endif

									@if($certificate->status->value === 'approved' && $certificate->file_path)
										<a href="{{ route('admin.certificates.download', $certificate->id) }}" class="btn btn-sm btn-primary">
											<i class="ki-duotone ki-file-down fs-5">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</a>
									@endif

									@if(!$certificate->file_path)
										@if($canGenerate)
											<form action="{{ route('admin.certificates.generate', $certificate->enrollment->id) }}" method="POST" class="d-inline">
												@csrf
												<button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Generate PDF sertifikat?')">
													<i class="ki-duotone ki-file fs-5">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													Generate
												</button>
											</form>
										@else
											<span class="badge badge-light-warning fs-7">Belum lulus ujian</span>
										@endif
									@endif
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="8" class="text-center text-muted py-10">Belum ada sertifikat</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		@if($certificates->hasPages())
			<div class="d-flex justify-content-between align-items-center mt-5">
				<div class="text-muted">
					Menampilkan {{ $certificates->firstItem() }} sampai {{ $certificates->lastItem() }} dari {{ $certificates->total() }} sertifikat
				</div>
				{{ $certificates->links() }}
			</div>
		@endif
	</div>
</div>
@endsection


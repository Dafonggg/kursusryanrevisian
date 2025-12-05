@extends('student.layouts.master')

@section('title', 'Materi: ' . $course->title . ' | Kursus Ryan Komputer')
@section('description', 'Daftar materi kursus')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Materi: {{ $course->title }}</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('student.materials') }}" class="text-muted text-hover-primary">Materi Online</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">{{ $course->title }}</li>
			</ul>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('student.materials') }}" class="btn btn-sm fw-bold btn-secondary">Kembali</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Materi</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $materials->count() }} materi tersedia</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		@if(isset($progressPercentage))
			<div class="mb-10">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="text-gray-700 fw-semibold">Progress Kursus</span>
					<span class="text-gray-900 fw-bold">{{ $progressPercentage }}%</span>
				</div>
				<div class="progress" style="height: 20px;">
					<div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $progressPercentage }}%</div>
				</div>
			</div>
		@endif

		@if($materials->count() > 0)
			<div class="table-responsive">
				<table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
					<thead>
						<tr class="fw-bold text-muted">
							<th class="min-w-50px">No</th>
							<th class="min-w-200px">Judul Materi</th>
							<th class="min-w-100px">Status</th>
							<th class="min-w-150px">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach($materialsWithStatus ?? [] as $index => $item)
							@php
								$material = $item['material'];
								$status = $item['status'];
							@endphp
							<tr>
								<td class="text-gray-900 fw-bold">{{ $index + 1 }}</td>
								<td class="text-gray-900 fw-semibold">{{ $material->title }}</td>
								<td>
									@if($status === 'completed')
										<span class="badge badge-light-success">
											<i class="ki-duotone ki-check-circle fs-7 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											Selesai
										</span>
									@elseif($status === 'locked')
										<span class="badge badge-light-danger">
											<i class="ki-duotone ki-lock fs-7 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											Terkunci
										</span>
									@else
										<span class="badge badge-light-primary">
											<i class="ki-duotone ki-arrow-right fs-7 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											Tersedia
										</span>
									@endif
								</td>
								<td>
									@if($status === 'locked')
										<span class="text-muted">Selesaikan materi sebelumnya</span>
									@else
										<a href="{{ route('student.materials.show', [$course->slug, $material->id]) }}" class="btn btn-sm btn-primary">
											<i class="ki-duotone ki-eye fs-5 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											{{ $status === 'completed' ? 'Lihat Ulang' : 'Buka Materi' }}
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			<div class="text-center py-10">
				<div class="mb-5">
					<i class="ki-duotone ki-information-5 fs-3x text-gray-400">
						<span class="path1"></span>
						<span class="path2"></span>
						<span class="path3"></span>
					</i>
				</div>
				<h3 class="text-gray-900 fw-bold mb-3">Belum Ada Materi</h3>
				<p class="text-gray-500">Materi untuk kursus ini belum tersedia.</p>
			</div>
		@endif
	</div>
</div>
@endsection


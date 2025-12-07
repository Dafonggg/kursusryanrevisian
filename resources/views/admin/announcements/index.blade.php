@extends('admin.layouts.master')

@section('title', 'Pengumuman - Admin')
@section('description', 'Kelola pengumuman')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Pengumuman</h1>
		</div>
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<a href="{{ route('admin.announcements.create') }}" class="btn btn-sm fw-bold btn-primary">Buat Pengumuman</a>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Daftar Pengumuman</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Semua pengumuman dalam sistem</span>
		</h3>
		<div class="card-toolbar">
			<form action="{{ route('admin.announcements.index') }}" method="GET" class="d-flex gap-2">
				<select name="course" class="form-select form-select-sm w-200px" onchange="this.form.submit()">
					<option value="">Semua Kursus</option>
					<option value="global" {{ request('course') === 'global' ? 'selected' : '' }}>Pengumuman Global</option>
					@foreach($courses as $course)
						<option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
					@endforeach
				</select>
			</form>
		</div>
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
						<th>Judul</th>
						<th>Kursus</th>
						<th>Pembuat</th>
						<th>Status</th>
						<th>Tanggal</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($announcements as $announcement)
						<tr>
							<td>
								<span class="fw-bold text-gray-800">{{ $announcement->title }}</span>
							</td>
							<td>
								@if($announcement->isGlobal())
									<span class="badge badge-light-primary">Global</span>
								@else
									<span class="text-gray-600">{{ $announcement->course->title ?? '-' }}</span>
								@endif
							</td>
							<td>{{ $announcement->creator->name ?? '-' }}</td>
							<td>
								@if($announcement->is_active)
									<span class="badge badge-light-success">Aktif</span>
								@else
									<span class="badge badge-light-danger">Nonaktif</span>
								@endif
							</td>
							<td>{{ $announcement->published_at?->format('d M Y H:i') ?? $announcement->created_at->format('d M Y H:i') }}</td>
							<td>
								<div class="d-flex gap-2">
									<a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-icon btn-light-primary" title="Edit">
										<i class="ki-duotone ki-pencil fs-5">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
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
					@empty
						<tr>
							<td colspan="6" class="text-center text-muted">Belum ada pengumuman</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="mt-5">
			{{ $announcements->links() }}
		</div>
	</div>
</div>
@endsection

@extends('instructor.layouts.master')

@section('title', 'Quick Actions - Metronic')
@section('description', 'Halaman Quick Actions untuk Instructor')

@section('toolbar')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Quick Actions</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('instructor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Instructor</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-gray-900">Quick Actions</li>
			</ul>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="row g-5 g-xl-10">
	<div class="col-12">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Quick Actions</span>
					<span class="text-gray-500 mt-1 fw-semibold fs-6">Aksi cepat untuk instruktur</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="row g-5">
					<div class="col-12">
						<div class="alert alert-info">
							<i class="ki-duotone ki-information-5 fs-2 me-2">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i>
							Quick actions untuk instruktur akan segera tersedia.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
	.quick-action-btn .card-hover {
		transition: all 0.3s ease;
		cursor: pointer;
	}
	.quick-action-btn .card-hover:hover {
		transform: translateY(-5px);
		box-shadow: 0 10px 20px rgba(0,0,0,0.1);
	}
	.quick-action-btn {
		color: inherit;
	}
	.quick-action-btn:hover {
		text-decoration: none;
	}
</style>
@endpush


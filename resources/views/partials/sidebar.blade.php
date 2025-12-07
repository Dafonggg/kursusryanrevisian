<!--begin::Sidebar-->
@php
	$role = Auth::user()->role;
	// Treat 'user' role as 'student' for sidebar navigation
	$effectiveRole = ($role === 'user') ? 'student' : $role;
	$dashboardRoute = $effectiveRole . '.dashboard';
@endphp
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<div class="d-flex align-items-center gap-3">
			<a href="{{ route($dashboardRoute) }}" class="d-flex align-items-center">
				<img alt="Logo" src="{{ asset('images/logokrk.png') }}" class="h-25px app-sidebar-logo-default" />
				<img alt="Logo" src="{{ asset('images/logokrk.png') }}" class="h-20px app-sidebar-logo-minimize" />
			</a>
			<div class="text-gray-800 fw-bold fs-5 app-sidebar-logo-default">
				Halo {{ Auth::user()->name }}
			</div>
		</div>
		<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
			<i class="ki-duotone ki-black-left-line fs-3 rotate-180">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
	</div>
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
			<div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
				<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
					
					{{-- Dashboard - All Roles --}}
					<div class="menu-item {{ request()->routeIs($effectiveRole . '.dashboard') ? 'here show' : '' }}">
						<a class="menu-link {{ request()->routeIs($effectiveRole . '.dashboard') ? 'active' : '' }}" href="{{ route($dashboardRoute) }}">
							<span class="menu-icon">
								<i class="ki-duotone ki-element-11 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title">Dashboard</span>
						</a>
					</div>

					{{-- Kursus - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.courses.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-book fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Kursus</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.courses') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.courses') ? 'active' : '' }}" href="{{ route('instructor.courses') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-book fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Kursus Saya</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.my-courses') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.my-courses') ? 'active' : '' }}" href="{{ route('student.my-courses') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-book fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Kursus Saya</span>
							</a>
						</div>
					@endif

					{{-- Materi Kursus - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.materials.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}" href="{{ route('admin.materials.overview') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-file fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Materi Kursus</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.materials.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.materials.*') ? 'active' : '' }}" href="{{ route('instructor.materials.overview') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-file fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Materi Kursus</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.materials*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.materials*') ? 'active' : '' }}" href="{{ route('student.materials') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-file fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Materi Online</span>
							</a>
						</div>
					@endif

					{{-- Transaksi/Pembayaran - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.payments.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-credit-cart fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title">Transaksi & Pembayaran</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.transactions') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.transactions') ? 'active' : '' }}" href="{{ route('instructor.transactions') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-wallet fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Riwayat Transaksi</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.payment') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.payment') ? 'active' : '' }}" href="{{ route('student.payment') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-wallet fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Pembayaran</span>
							</a>
						</div>
					@endif

					{{-- Ujian Akhir - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.exam-results.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.exam-results.*') ? 'active' : '' }}" href="{{ route('admin.exam-results.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-notepad-edit fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Hasil Ujian</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('instructor.exams.*') ? 'here show' : '' }}">
							<span class="menu-link">
								<span class="menu-icon">
									<i class="ki-duotone ki-notepad-edit fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Ujian Akhir</span>
								<span class="menu-arrow"></span>
							</span>
							<div class="menu-sub menu-sub-accordion {{ request()->routeIs('instructor.exams.*') ? 'show' : '' }}">
								<div class="menu-item">
									<a class="menu-link {{ request()->routeIs('instructor.exams.overview') || request()->routeIs('instructor.exams.index') || request()->routeIs('instructor.exams.create-*') ? 'active' : '' }}" href="{{ route('instructor.exams.overview') }}">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">Kelola Soal</span>
									</a>
								</div>
								<div class="menu-item">
									<a class="menu-link {{ request()->routeIs('instructor.exams.submissions') || request()->routeIs('instructor.exams.show-submission') || request()->routeIs('instructor.exams.grade') ? 'active' : '' }}" href="{{ route('instructor.exams.submissions') }}">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">Nilai Jawaban</span>
									</a>
								</div>
							</div>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.exams.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.exams.*') ? 'active' : '' }}" href="{{ route('student.exams.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-notepad-edit fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Ujian Akhir</span>
							</a>
						</div>
					@endif

					{{-- Sertifikat - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.certificates.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}" href="{{ route('admin.certificates.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-award fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Sertifikat</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.certificates') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.certificates') ? 'active' : '' }}" href="{{ route('instructor.certificates') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-award fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Sertifikat</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.certificate') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.certificate') ? 'active' : '' }}" href="{{ route('student.certificate') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-award fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Sertifikat</span>
							</a>
						</div>
					@endif

					{{-- Pengumuman - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.announcements.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-notification fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
								<span class="menu-title">Pengumuman</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.announcements.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.announcements.*') ? 'active' : '' }}" href="{{ route('instructor.announcements.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-notification fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
								<span class="menu-title">Pengumuman</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.announcements.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.announcements.*') ? 'active' : '' }}" href="{{ route('student.announcements.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-notification fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
								<span class="menu-title">Pengumuman</span>
							</a>
						</div>
					@endif

					{{-- Admin Only: Pengguna --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.users.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-people fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
										<span class="path5"></span>
									</i>
								</span>
								<span class="menu-title">Pengguna</span>
							</a>
						</div>
					@endif

					{{-- Admin Only: Keuangan --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.financial.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.financial.*') ? 'active' : '' }}" href="{{ route('admin.financial.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-wallet fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Keuangan</span>
							</a>
						</div>
					@endif

					{{-- Chat - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.chat.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" href="{{ route('admin.chat.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-message-text-2 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
								<span class="menu-title">Chat</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.messages') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.messages') ? 'active' : '' }}" href="{{ route('instructor.messages') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-message-text-2 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
								<span class="menu-title">Chat</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.chat') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.chat') ? 'active' : '' }}" href="{{ route('student.chat') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-message-text-2 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title">Chat</span>
							</a>
						</div>
					@endif

					{{-- Admin Only: Analisis Peserta --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.analytics.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-chart fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Analisis Peserta</span>
							</a>
						</div>
					@endif

					{{-- Profil - Role Specific --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.profile.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-profile-user fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title">Profil</span>
							</a>
						</div>
					@elseif($effectiveRole === 'instructor')
						<div class="menu-item {{ request()->routeIs('instructor.profile.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('instructor.profile.*') ? 'active' : '' }}" href="{{ route('instructor.profile') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-profile-user fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title">Profil</span>
							</a>
						</div>
					@elseif($effectiveRole === 'student')
						<div class="menu-item {{ request()->routeIs('student.profile*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('student.profile*') ? 'active' : '' }}" href="{{ route('student.profile') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-profile-user fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title">Profil</span>
							</a>
						</div>
					@endif

					{{-- Admin Only: Pengaturan --}}
					@if($effectiveRole === 'admin')
						<div class="menu-item {{ request()->routeIs('admin.settings.*') ? 'here show' : '' }}">
							<a class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
								<span class="menu-icon">
									<i class="ki-duotone ki-setting-2 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title">Pengaturan</span>
							</a>
						</div>
					@endif

				</div>
			</div>
		</div>
	</div>
	<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
	</div>
</div>
<!--end::Sidebar-->

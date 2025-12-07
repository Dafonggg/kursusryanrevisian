@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<header class="site-header" style="padding-top: 100px; padding-bottom: 40px;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-white mb-0" style="font-size: 2rem;">Keranjang</h1>
            </div>
        </div>
    </div>
</header>

<section class="section-padding section-bg" style="padding-top: 40px;">
    <div class="container">
        <div class="row">
            @if(session('success'))
                <div class="col-12">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="col-12">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(isset($removedCourses) && !empty($removedCourses))
                <div class="col-12">
                    <div class="alert alert-warning">
                        <strong>Perhatian:</strong> Kursus berikut sudah terdaftar dan telah dihapus dari keranjang: 
                        <strong>{{ implode(', ', $removedCourses) }}</strong>
                    </div>
                </div>
            @endif

            @if(empty($courses))
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Keranjang Anda kosong. <a href="{{ route('daftar-kursus') }}">Lihat kursus</a></p>
                    </div>
                </div>
            @else
                {{-- Cart Items --}}
                <div class="col-lg-7 col-12 mb-4 mb-lg-0">
                    <div class="custom-block bg-white shadow-lg mb-3 p-3">
                        @foreach($courses as $course)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                <img src="{{ $course['image'] ? asset('storage/' . $course['image']) : asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" 
                                     class="img-fluid me-3 rounded flex-shrink-0" 
                                     style="width: 60px; height: 60px; object-fit: cover;"
                                     alt="{{ $course['title'] }}">
                                <div class="flex-grow-1 me-2 overflow-hidden">
                                    <h6 class="mb-1 text-truncate" style="font-size: 0.9rem;">{{ $course['title'] }}</h6>
                                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Rp {{ number_format($course['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <p class="mb-1 fw-bold" style="font-size: 0.85rem;">Rp {{ number_format($course['subtotal'], 0, ',', '.') }}</p>
                                    <form action="{{ route('cart.remove', $course['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="font-size: 0.75rem;">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="col-lg-5 col-12">
                    <div class="custom-block bg-white shadow-lg p-3 position-sticky" style="top: 100px;">
                        <h6 class="mb-3 fw-bold">Ringkasan Pesanan</h6>
                        
                        {{-- Items count --}}
                        <div class="d-flex justify-content-between mb-2 text-muted" style="font-size: 0.85rem;">
                            <span>Jumlah Item:</span>
                            <span>{{ count($courses) }} kursus</span>
                        </div>
                        
                        <hr class="my-2">
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout') }}" class="btn custom-btn py-2">Checkout</a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 py-2">Kosongkan</button>
                            </form>
                            <a href="{{ route('daftar-kursus') }}" class="btn btn-outline-primary py-2">Lanjut Belanja</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    @media (max-width: 767.98px) {
        .section-padding {
            padding: 30px 0 !important;
        }
        .custom-block {
            border-radius: 12px;
        }
    }
    
    @media (max-width: 575.98px) {
        header.site-header {
            padding-top: 80px !important;
            padding-bottom: 30px !important;
        }
        header.site-header h1 {
            font-size: 1.5rem !important;
        }
    }
</style>
@endsection


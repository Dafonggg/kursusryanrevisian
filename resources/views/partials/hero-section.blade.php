<section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
    <div class="container">
        <div class="row">

            <div class="col-lg-8 col-12 mx-auto">
                <h1 class="text-white text-center">Selamat datang di Kursus Ryan Komputer</h1>

                <h6 class="text-center">Kursus Ryan Komputer adalah platform kursus komputer yang menyediakan berbagai macam kursus untuk para pelajar dan mahasiswa</h6>

                <form method="get" action="{{ route('daftar-kursus') }}" class="custom-form mt-4 pt-2 mb-lg-0 mb-5" role="search">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bi-search" id="basic-addon1">
                            
                        </span>

                        <input name="search" type="search" class="form-control" id="search" placeholder="Search Your Kursus Favorite" aria-label="Search">

                        <button type="submit" class="form-control">Search</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>


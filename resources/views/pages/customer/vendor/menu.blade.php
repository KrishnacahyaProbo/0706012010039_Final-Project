@section('title', 'Detail Vendor')

<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-grid gap-3">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-3">
                    <img src="https://laravel.com/img/logotype.min.svg" alt="" class="card-img-top rounded-0"
                        loading="lazy">
                </div>

                <div class="col-md-9">
                    <div class="card-body">
                        <h3 class="card-title">(Nama Vendor)</h3>

                        <div class="d-grid text-secondary gap-1">
                            <div class="d-flex gap-2">
                                <i class="bi bi-star"></i>
                                <p class="card-text">(Skala Rating 1-5)/5</p>
                            </div>

                            <div class="d-flex gap-2">
                                <i class="bi bi-geo-alt"></i>
                                <p class="card-text truncate">(Alamat - Jarak kilometer dari alamat customer)km</p>
                            </div>

                            <div class="d-flex gap-2">
                                <i class="bi bi-truck"></i>
                                <p class="card-text">Rp(Ongkir)</p>
                            </div>
                        </div>

                        <hr>

                        <small class="card-text">(About us) Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Impedit nemo quia repellat possimus perspiciatis doloribus saepe nobis accusantium ab
                            dolorum nihil, autem iure laboriosam nulla rerum illum soluta eum a!</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <h1>Pilih Menu</h1>

            <div class="d-grid d-lg-flex gap-3">
                <div class="w-100">
                    <div class="d-grid gap-2">
                        {{-- TODO: fullcalendar --}}
                        <div id="calendar">
                            <strong class="text-danger">disini diisi komponen fullcalendar masing2 vendor</strong>
                        </div>
                        {{-- TODO end here --}}
                        <x-button class="w-100">View Cart</x-button>
                    </div>
                </div>

                <div class="w-100">
                    <div class="card">
                        <div class="card-body d-grid gap-3 p-0">
                            <img src="https://laravel.com/img/logotype.min.svg" alt=""
                                class="card-img-top rounded-0" loading="lazy">

                            <h3 class="card-title">(Nama Menu)</h3>

                            <small class="card-text text-secondary">(Deskripsi Menu) Lorem ipsum
                                dolor sit amet consectetur adipisicing elit. Quod odio sit, corporis id nihil
                                eius similique soluta ut ipsum vel impedit rerum possimus modi iusto cumque sint
                                dolores inventore! Delectus.</small>

                            <h5>Rp(Harga)/pcs</h5>

                            <div class="d-flex align-items-center gap-3">
                                <span>Porsi</span>
                                <div>
                                    <button class="btn btn-outline-secondary rounded-pill mx-1 px-3"
                                        type="button">Small</button>
                                    <button class="btn btn-primary rounded-pill mx-1 px-3"
                                        type="button">Medium</button>
                                    <button class="btn btn-outline-secondary rounded-pill mx-1 px-3"
                                        type="button">Large</button>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <span>Kuantitas</span>
                                <div class="d-flex align-items-center border-secondary rounded border">
                                    <button class="btn border-0">
                                        <i class="bi bi-dash-lg text-primary"></i>
                                    </button>
                                    <span class="mx-2">0</span>
                                    <button class="btn border-0">
                                        <i class="bi bi-plus-lg text-primary"></i>
                                    </button>
                                </div>
                            </div>

                            <x-button class="w-100">Add to Cart</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

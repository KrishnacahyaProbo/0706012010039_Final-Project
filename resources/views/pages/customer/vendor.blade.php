@section('title', 'Vendor')

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
        <div>
            @section('page_title', 'Jelajahi Vendor Katering')
        </div>

        <div class="d-flex gap-2">
            <input type="search" class="form-control" id="searchInput" placeholder="Cari Vendor" aria-label="Cari vendor katering"
                aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2" onclick="searchVendor()">Cari</button>
        </div>
        <div id="vendorContainer" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <!-- Vendors will be dynamically added here -->
        </div>
        {{-- <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @for ($i = 0; $i < 6; $i++)
                <div class="col">
                    <div class="card gap-3">
                        <img src="" alt="" class="card-img-top rounded-0" loading="lazy">

                        <div class="card-body p-0">
                            <h3 class="card-title">
                                <a href="#" class="stretched-link">(Nama Vendor)</a>
                            </h3>

                            <div class="d-grid text-secondary gap-1">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-star"></i>
                                    <span class="card-text">(Skala Rating 1-5)/5</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <i class="bi bi-geo-alt"></i>
                                    <p class="card-text truncate">(Alamat - Jarak kilometer dari alamat customer)km</p>
                                </div>

                                <div class="d-flex gap-2">
                                    <i class="bi bi-truck"></i>
                                    <p class="card-text">Rp.(Ongkir)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div> --}}
    </div>
    <script src="{{asset('js/vendors.js')}}"></script>

</x-app-layout>

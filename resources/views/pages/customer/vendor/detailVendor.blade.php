@section('title', $vendor->name)

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
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <img src="{{ $vendor->profile_photo_url }}" alt="" class="card-img-top rounded-0"
                            loading="lazy">
                    </div>

                    <div class="col-md-9">
                        <h3 class="card-title">{{ $vendor->name }}</h3>

                        <div class="d-grid text-secondary gap-1">
                            <div>
                                <a href="/testimonies/{{ $vendor->id }}" class="d-inline-flex gap-2">
                                    <i class="bi bi-star" title="Testimoni"></i>
                                    <p class="card-text">{{ $vendor->rating ?? '-' }}/5,0</p>
                                </a>
                            </div>

                            <div class="d-flex gap-2">
                                <i class="bi bi-geo-alt" title="Alamat - Jarak vendor terhadap Anda"></i>
                                <p class="card-text" id="distance-info"></p>
                            </div>

                            <div class="d-flex gap-2">
                                <i class="bi bi-truck" title="Ongkos Kirim"></i>
                                <p class="card-text">
                                    Rp{{ number_format($vendor->Delivery->shipping_cost ?? '0', 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <hr>

                        <small class="card-text">
                            <pre class="mb-0">{{ $vendor->UserSetting->about_us ?? 'No description yet.' }}</pre>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Pilih Menu</h1>
                <div>
                    <x-button data-bs-toggle="modal" data-bs-target="#katalog">Katalog</x-button>

                    @include('pages.customer.vendor.include.katalogModal')
                </div>
            </div>

            <div class="d-grid d-lg-flex gap-3">
                <div class="w-100">
                    <div class="sticky-md-top">
                        <div class="d-grid gap-2">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span>Klik tanggal <strong>H-{{ $vendor->UserSetting->confirmation_days }}</strong>
                                        dari jadwal pemesanan</span>
                                </div>
                                <div id="calendar_menu" class="card-body"></div>
                            </div>

                            <a href="{{ route('cart.index') }}" class="btn btn-primary">View Cart</a>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="w-100 d-grid gap-2" id="menuCart"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var vendorData = <?php echo json_encode([
            'id' => $vendor->id,
            'latitude' => $vendor->latitude,
            'longitude' => $vendor->longitude,
            'address' => $vendor->address,
            'menu' => $vendor->menu,
        ]); ?>;

        var public = "{{ asset('') }}";
    </script>
    <script src="{{ asset('/js/detailVendor.js') }}"></script>
    <script src="{{ asset('/js/formatRupiah.js') }}"></script>
</x-app-layout>

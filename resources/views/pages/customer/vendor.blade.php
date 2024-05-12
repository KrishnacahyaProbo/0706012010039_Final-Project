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

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-3">
                    <div class="d-grid">
                        <span class="text-secondary">Pilih lokasi Anda</span>
                        <strong id="customer_address">{{ $user_setting != null ? $user_setting->address : '-' }}
                            <a href="{{ route('setting.index') }}">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </a>
                        </strong>
                    </div>

                    <div>
                        <x-secondary-button class="d-none d-md-block" id="detect_geolocation"
                            onclick="chooseCustomerLocation()">Use Geolocation</x-secondary-button>
                        <x-secondary-button class="d-block d-md-none" id="detect_geolocation" title="Use Geolocation"
                            onclick="chooseCustomerLocation()">
                            <i class="bi bi-crosshair"></i>
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <input type="search" class="form-control" id="searchInput" placeholder="Cari Vendor"
                aria-label="Cari vendor katering" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2" onclick="searchVendor()">Search</button>
        </div>
        <div id="vendorContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"></div>
    </div>

    @section('js')
        <script>
            var customerLatitude = "{{ $user_setting->latitude }}";
            var customerLongitude = "{{ $user_setting->longitude }}";
        </script>
        <script src="{{ asset('js/vendors.js') }}"></script>
    @endsection
</x-app-layout>

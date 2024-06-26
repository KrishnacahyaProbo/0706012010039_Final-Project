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

    @if (Auth::user()->hasRole('vendor') &&
            ($authDelivery === null || $confirmationDays->confirmation_days === null || $balance === null))
        @include('pages.users.include.settingModal')
    @endif
    @if (Auth::user()->hasRole('customer') && $balance === null)
        @include('pages.users.include.settingModal')
    @endif

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Jelajahi Vendor Katering')
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-3">
                    <div class="d-grid">
                        <span class="text-secondary">Alamat Pengiriman</span>
                        <strong id="customer_address">{{ $user_setting != null ? $user_setting->address : '-' }}
                            <a href="{{ route('setting.index') }}">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </a>
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <input type="search" class="form-control" id="searchInput" placeholder="Cari Vendor"
                aria-label="Cari vendor katering" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2" onclick="searchVendor()">Cari</button>
        </div>
        <div id="vendorContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"></div>
    </div>

    @section('js')
        <script>
            var customerLatitude = "{{ $user_setting->latitude }}";
            var customerLongitude = "{{ $user_setting->longitude }}";
        </script>
        <script src="{{ asset('js/setting.js') }}"></script>
        <script src="{{ asset('js/vendors.js') }}"></script>
    @endsection
</x-app-layout>

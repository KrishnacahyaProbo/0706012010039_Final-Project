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
            <input type="search" class="form-control" id="searchInput" placeholder="Cari Vendor"
                aria-label="Cari vendor katering" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2" onclick="searchVendor()">Search</button>
        </div>
        <div id="permissionDenied"></div>
        <div id="vendorContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"></div>
    </div>

    @section('js')
        <script src="{{ asset('js/vendors.js') }}"></script>
    @endsection
</x-app-layout>

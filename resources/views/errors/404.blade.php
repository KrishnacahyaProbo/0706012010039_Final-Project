@section('title', '404 - Page Not Found')

<x-guest-layout>
    <div class="d-grid gap-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <img src="{{ url('images/brand/logo.svg') }}" alt="Logo" id="logo">
                    <div class="vr"></div>
                    <h3>Rumah Katering</h3>
                </div>
            </div>
            <div class="card-body d-grid gap-3 text-center">
                <h1 class="display-1 text-danger">404</h1>
                <p class="fs-3">Page Not Found</p>
                <hr class="my-1">
                <span>Halaman yang Anda cari tidak ditemukan. Silakan coba kata kunci lain.</span>
                <div class="my-3">
                    <a href="{{ route('home') }}" class="btn btn-primary d-inline">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

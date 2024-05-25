@section('title', '404 - Page Not Found')

<x-guest-layout>
    <div class="d-grid gap-5">
        <div class="card">
            <div class="card-body d-grid gap-3 pb-0 text-center">
                <div>
                    <h1 class="display-1 text-danger">404</h1>
                    <p class="fs-3">Page Not Found</p>
                </div>

                <div class="alert alert-danger d-grid gap-3 text-center" role="alert">
                    <i class="bi bi-exclamation-circle-fill display-1"></i>
                    <span>Halaman yang Anda cari tidak ditemukan. Silakan coba kata kunci lain.</span>
                    <hr class="my-1">
                    <div class="my-1">
                        <a href="{{ route('home') }}" class="btn btn-primary d-inline">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3">
            <img src="{{ url('images/brand/logo.svg') }}" alt="Logo" id="logo">
            <div class="vr"></div>
            <h3>Rumah Katering</h3>
        </div>
    </div>
</x-guest-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- HTML Meta Tags --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="Rancang Bangun, Lokapasar, Katering Harian" />
    <meta name="description"
        content="Rancang Bangun Lokapasar Katering Harian Berbasis Web (Web-Based Daily Catering Marketplace Design)." />
    <meta name="owner" content="Probo Krishnacahya." />
    <meta name="theme-color" content="#842029">

    {{-- Page Title --}}
    <title>@yield('title')</title>

    {{-- Scripts --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Favicon --}}
    <link rel="icon" href="{{ url('images/brand/logo.svg?v=2') }}" type="image/svg" />

    {{-- Libraries --}}
    <link rel="stylesheet" href="{{ asset('libraries/css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('libraries/css/datatables.min-bs5-2.0.3.css') }}">
    <link rel="stylesheet" href="{{ asset('libraries/css/material_red.css') }}">
    <link rel="stylesheet" href="{{ asset('libraries/css/jquery-ui-1.12.1.css') }}">
    <script src="{{ asset('libraries/js/fullcalendar-6.1.11_index.global.min.js') }}"></script>
    <script src="{{ asset('libraries/js/fullcalendar-bootstrap5-6.1.11_index.global.min.js') }}"></script>
</head>

<body>
    <div>
        <x-header />

        <main>
            <div class="container my-5">
                <h1>@yield('page_title')</h1>

                {{ $slot }}
            </div>
        </main>
    </div>

    @yield('js')

    {{-- Libraries --}}
    <script src="{{ asset('libraries/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('libraries/js/jquery.validate-1.20.0.min.js') }}"></script>
    <script src="{{ asset('libraries/js/leaflet.js') }}"></script>
    <script src="{{ asset('libraries/js/datatables.min-bs5-dt-2.0.3-r-3.0.0.js') }}"></script>
    <script src="{{ asset('libraries/js/flatpickr.js') }}"></script>
    <script src="{{ asset('libraries/js/jquery-ui-1.12.1.js') }}"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/formatRupiah.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            getLocation(null);
        });
    </script>
</body>

</html>

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

    {{-- Library --}}
    <link rel="stylesheet" href="{{ asset('libraries/css/leaflet.css') }}">
    <script src="{{ asset('libraries/js/jquery-3.7.1.min.js') }}"></script>
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

    {{-- Library --}}
    <script src="{{ asset('libraries/js/leaflet.js') }}"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('js/map.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            getLocation(null);
        });
    </script>
</body>

</html>

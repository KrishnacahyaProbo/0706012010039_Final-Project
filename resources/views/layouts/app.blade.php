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
</head>

<body>
    <div>
        <!-- Page Heading -->
        @if (isset($header))
            <header>
                <div>
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="container my-5">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('js')
</body>

</html>

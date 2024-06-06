@php
    $routeName = request()->route() ? request()->route()->getName() : '';

    $isMenu = Str::startsWith($routeName, 'menu');
    $isSchedule = Str::startsWith($routeName, 'schedule');
    $isOrder = Str::startsWith($routeName, 'order');
    $isVendor = Str::startsWith($routeName, 'vendor');
    $isCart = Str::startsWith($routeName, 'cart');
    $isProfile = Str::startsWith($routeName, 'profile');
    $isSetting = Str::startsWith($routeName, 'setting');
    $isCredit = Str::startsWith($routeName, 'credit');
@endphp

<nav class="navbar navbar-light navbar-expand-lg border-bottom bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ url('images/brand/logo.svg') }}" alt="Logo" id="logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarNav">
            @auth
                <div class="navbar-nav mx-lg-4 my-lg-0 my-3 gap-2">
                    @if (Auth::user()->hasRole('vendor'))
                        <a class="nav-link {{ $isMenu ? 'active' : '' }}" href="{{ route('menu.index') }}">Menu</a>
                        <a class="nav-link {{ $isSchedule ? 'active' : '' }}"
                            href="{{ route('schedule.vendor', ['vendor_name' => Auth::user()->name]) }}">Jadwal</a>
                        <a class="nav-link {{ $isOrder ? 'active' : '' }}" href="{{ route('order.index') }}">Pesanan</a>
                    @else
                        <a class="nav-link {{ $isVendor ? 'active' : '' }}" href="{{ route('vendor.index') }}">Vendor</a>
                        <a class="nav-link {{ $isCart ? 'active' : '' }}" href="{{ route('cart.index') }}">Keranjang</a>
                        <a class="nav-link {{ $isOrder ? 'active' : '' }}" href="{{ route('order.index') }}">Pesanan</a>
                    @endif
                </div>

                <div class="dropdown mb-1 ms-auto">
                    <button class="btn dropdown-toggle border-0 p-0" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @isset(Auth::user()->profile_photo_path)
                            @if (str_contains(Auth::user()->profile_photo_path, 'https://'))
                                <img src="{{ Auth::user()->profile_photo_path }}" alt="{{ Auth::user()->name }}"
                                    class="object-fit-contain" id="profile_photo" title="{{ Auth::user()->name }}">
                            @else
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                    alt="{{ Auth::user()->name }}" class="object-fit-contain" id="profile_photo"
                                    title="{{ Auth::user()->name }}">
                            @endif
                        @else
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                    class="object-fit-contain" id="profile_photo" title="{{ Auth::user()->name }}">
                            @endif
                        @endisset
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end mt-2" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item {{ $isProfile ? 'active' : '' }}"
                                href="{{ route('profile.show') }}">{{ __('Profil') }}</a>
                            <a class="dropdown-item {{ $isSetting ? 'active' : '' }}"
                                href="{{ route('setting.index') }}">{{ __('Pengaturan') }}</a>
                            <a class="dropdown-item {{ $isCredit ? 'active' : '' }}"
                                href="{{ route('credit.index') }}">{{ __('Kredit') }}</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">{{ __('Keluar') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
            @guest
                <div class="d-grid d-lg-flex my-lg-0 mb-1 ms-auto mt-3 gap-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Daftar</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                </div>
            @endguest
        </div>
    </div>
</nav>

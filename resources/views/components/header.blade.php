@php
    $routeName = request()->route()->getName();

    $isMenu = Str::startsWith($routeName, 'menu');
    $isSchedule = Str::startsWith($routeName, 'schedule');
    $isOrder = Str::startsWith($routeName, 'order');
    $isVendor = Str::startsWith($routeName, 'vendor');
    $isCart = Str::startsWith($routeName, 'cart');
    $isProfile = Str::startsWith($routeName, 'profile');
    $isSettings = Str::startsWith($routeName, 'users.settings');
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
                <div class="navbar-nav mx-lg-4 my-lg-0 my-3 gap-1">
                    @if (Auth::user()->hasRole('vendor'))
                        <a class="nav-link {{ $isMenu ? 'active' : '' }}" href="{{ route('menu.index') }}">Menu</a>
                        <a class="nav-link {{ $isSchedule ? 'active' : '' }}"
                            href="{{ route('schedule.vendor', ['vendor_name' => Auth::user()->name]) }}">Schedule</a>
                        <a class="nav-link {{ $isOrder ? 'active' : '' }}" href="{{ url('/order') }}">Order</a>
                    @else
                        <a class="nav-link {{ $isVendor ? 'active' : '' }}" href="{{ route('vendor.index') }}">Vendor</a>
                        <a class="nav-link {{ $isCart ? 'active' : '' }}" href="{{ url('/cart') }}">Cart</a>
                        <a class="nav-link {{ $isOrder ? 'active' : '' }}" href="{{ url('/order') }}">Order</a>
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
                                href="{{ route('profile.show') }}">{{ __('Profile') }}</a>
                            <a class="dropdown-item {{ $isSettings ? 'active' : '' }}"
                                href="{{ route('setting.index') }}">{{ __('Setting') }}</a>
                            <a class="dropdown-item {{ $isCredit ? 'active' : '' }}"
                                href="{{ route('credit') }}">{{ __('Credit') }}</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
            @guest
                <div class="d-grid d-lg-flex my-lg-0 mb-1 ms-auto mt-3 gap-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
                </div>
            @endguest
        </div>
    </div>
</nav>

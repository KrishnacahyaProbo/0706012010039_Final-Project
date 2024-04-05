@php
    $routeName = request()->route()->getName();

    $isMenu = Str::startsWith($routeName, 'users.menu');
    $isSchedule = Str::startsWith($routeName, 'schedule');
    $isOrder = Str::startsWith($routeName, 'order');
    $isProfile = Str::startsWith($routeName, 'profile');
@endphp

<nav class="navbar navbar-light navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <img src="{{ url('images/brand/logo.svg') }}" alt="Logo" id="logo">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarNav">
            @auth
                <div class="navbar-nav mx-lg-4 my-lg-0 my-3 gap-1">
                    <a class="nav-link {{ $isMenu ? 'active' : '' }}" href="{{ url('/users/menu') }}">Menu</a>
                    <a class="nav-link {{ $isSchedule ? 'active' : '' }}" href="{{ url('/schedule') }}">Schedule</a>
                    <a class="nav-link {{ $isOrder ? 'active' : '' }}" href="{{ url('/order') }}">Order</a>
                </div>
            @endauth
            @auth
                <div class="dropdown ms-auto">
                    <button class="btn dropdown-toggle border-0 p-0" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                class="object-fit-contain" id="profile_photo">
                        @else
                            {{ Auth::user()->name }}

                            <svg class="-me-0.5 ms-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end mt-2" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item {{ $isProfile ? 'active' : '' }}"
                                href="{{ route('profile.show') }}">{{ __('Profile') }}</a>
                        </li>
                        <hr class="my-1">
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
                <div class="d-flex ms-auto gap-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
                </div>
            @endguest
        </div>
    </div>
</nav>

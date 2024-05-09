@section('title', 'Register')

<x-guest-layout>
    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Halo, Buat Akun dan Bergabung Sekarang')
        </div>

        <div class="d-grid gap-2">
            <div>
                <x-authentication-card>
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="d-grid gap-3">
                            <div>
                                <x-label for="role" value="{{ __('Peran') }}" />

                                <div class="d-flex gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="vendor"
                                            value="vendor">
                                        <label class="form-check-label" for="vendor">Vendor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="customer"
                                            value="customer">
                                        <label class="form-check-label" for="customer">Customer</label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-label for="name" value="{{ __('Nama') }}" />
                                <x-input id="name" type="text" name="name" :value="old('name')" required
                                    autofocus autocomplete="name" />
                            </div>

                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" type="email" name="email" :value="old('email')" required
                                    autocomplete="username" />
                            </div>

                            <div>
                                <x-label for="password" value="{{ __('Password') }}" />
                                <x-input id="password" type="password" name="password" required
                                    autocomplete="new-password" />
                            </div>

                            <div>
                                <x-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" />
                                <x-input id="password_confirmation" type="password" name="password_confirmation"
                                    required autocomplete="new-password" />
                            </div>

                            {{-- <div class="d-grid gap-2">
                                <div>
                                    <x-label for="address" value="{{ __('Alamat') }}" />
                                    <x-input type="search" id="searchInput" class="form-control"
                                        placeholder="Cari Alamat" />

                                    <div id="addressDropdown"></div>
                                </div>

                                <x-input type="text" id="selectedAddress" value="" class="d-none" readonly />

                                <div>
                                    <div id="permissionDenied"></div>
                                    <div id="map" class="rounded-1"></div>
                                </div>

                                <div class="d-none">
                                    <div>
                                        <x-label for="latitude" value="{{ __('Latitude') }}"></x-label>
                                        <x-input name="latitude" id="latitude" type="text" autocomplete="off"
                                            class="form-control" readonly />
                                    </div>

                                    <div>
                                        <x-label for="longitude" value="{{ __('Longitude') }}"></x-label>
                                        <x-input name="longitude" id="longitude" type="text" autocomplete="off"
                                            class="form-control" readonly />
                                    </div>

                                    <div>
                                        <x-label for="address" value="{{ __('Alamat') }}" />
                                        <textarea placeholder="Address" name="address" id="address" rows="4" class="form-control" readonly></textarea>
                                    </div>
                                </div>

                                <div>
                                    <span id="address_text" class="text-secondary"></span>
                                </div>
                            </div> --}}

                            <x-button>{{ __('Register') }}</x-button>
                        </div>
                    </form>
                </x-authentication-card>
            </div>

            <div>
                <div class="d-flex gap-1">
                    <p>Sudah punya akun?</p>
                    <a href="{{ route('login') }}">Log In</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

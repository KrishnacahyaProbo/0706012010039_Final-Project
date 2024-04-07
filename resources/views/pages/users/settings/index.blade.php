@section('title', 'Menu')

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

    @section('page_title', 'Pengaturan')

    <div class="d-grid gap-3">
        <div class="card">
            <div class="d-grid gap-3">
                <form method="POST" id="pengiriman" class="mt-4">
                    @csrf

                    <h3>Pengiriman</h3>

                    <div class="mb-3">
                        <label for="distance_between" class="form-label">{{ __('Jarak Maksimum') }}</label>
                        <input id="distance_between" type="number" name="distance_between" class="form-control" value="{{ $delivery!= null ? $delivery->distance_between : '' }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_cost" class="form-label">{{ __('Ongkos Pengiriman') }}</label>
                        <input id="shipping_cost" type="number" name="shipping_cost" class="form-control" value =
                        "{{ $delivery!= null ? $delivery->shipping_cost : '' }}" required autofocus>
                    </div>

                    <button class="btn btn-primary w-100" id="pengirimanBtn" type="button" onclick="settingsDelivery()">{{ __('Save') }}</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="d-grid gap-3">
                <form method="POST" id="pesanan" class="mt-4">
                    @csrf

                    <h3 class="mb-4">Hari Konfirmasi Pesanan</h3>

                    <div class="mb-3">
                        <label for="confirmation_days" class="form-label">{{ __('Batas Hari Terakhir Sebelum Tanggal Pemesanan') }}</label>
                        <input id="confirmation_days" type="number" name="confirmation_days" class="form-control" value="{{ $user_setting != null ? $user_setting->confirmation_days : "" }}" required autofocus>
                    </div>

                    <button class="btn btn-primary w-100" type="button" onclick="userSettings()">{{ __('Save') }}</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="d-grid gap-3">
                <form method="POST" id="alamat">
                    @csrf

                    <h3>Alamat</h3>

                    <div class="container mt-4">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <x-label for="address" value="{{ __('Alamat') }}" />
                                    <x-input type="text" id="searchInput" class="form-control" />
                                    <div id="addressDropdown"></div>
                                </div>

                                <x-input type="text" id="selectedAddress" value="" class="d-none" readonly />

                                <div class="mb-3">
                                    <div id="map" class="rounded"></div>
                                </div>

                                <div class="d-none">
                                    <div class="mb-3">
                                        <x-label for="latitude" value="{{ __('Latitude') }}" />
                                        <x-input name="latitude" id="latitude" type="text" autocomplete="off" class="form-control" readonly />
                                    </div>

                                    <div class="mb-3">
                                        <x-label for="longitude" value="{{ __('Longitude') }}" />
                                        <x-input name="longitude" id="longitude" type="text" autocomplete="off" class="form-control" readonly />
                                    </div>

                                    <div class="mb-3">
                                        <x-label for="address" value="{{ __('Alamat') }}" />
                                        <textarea placeholder="Address" name="address" id="address" rows="4" class="form-control" readonly></textarea>
                                    </div>
                                </div>

                                <div>
                                    <span id="address_text" class="text-secondary"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary w-100" type="button" onclick="setAlamat()">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/userSettings.js') }}"></script>
</x-app-layout>

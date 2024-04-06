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
                <form method="POST" id="pengiriman">
                    @csrf

                    <h3>Pengiriman</h3>

                    <div>
                        <x-label for="distance_between" value="{{ __('Jarak Maksimum') }}" />
                        <x-input id="distance_between" type="number" name="distance_between" :value="old('distance_between')" required
                            autofocus />
                    </div>
                    <div>
                        <x-label for="shipping_cost" value="{{ __('Ongkos Pengiriman') }}" />
                        <x-input id="shipping_cost" type="number" name="shipping_cost" :value="old('shipping_cost')" required
                            autofocus />
                    </div>

                    <x-button class="w-100" id="pengirimanBtn" type="submit">{{ __('Save') }}</x-button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="d-grid gap-3">
                <form method="POST" id="pesanan">
                    @csrf

                    <h3>Hari Konfirmasi Pesanan</h3>

                    <div>
                        <x-label for="confirmation_days"
                            value="{{ __('Batas Hari Terakhir Sebelum Tanggal Pemesanan') }}" />
                        <x-input id="confirmation_days" type="number" name="confirmation_days" :value="old('confirmation_days')"
                            required autofocus />
                    </div>

                    <x-button class="w-100">{{ __('Save') }}</x-button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="d-grid gap-3">
                <form method="POST" id="alamat">
                    @csrf

                    <h3>Alamat</h3>

                    <div>
                        <div class="d-grid gap-2">
                            <div>
                                <x-label for="address" value="{{ __('Alamat') }}" />
                                <x-input type="text" id="searchInput" class="form-control" />

                                <div id="addressDropdown"></div>
                            </div>

                            <x-input type="text" id="selectedAddress" value="" class="d-none" readonly />

                            <div>
                                <div id="map" class="rounded-1">aaaa</div>
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
                        </div>
                    </div>

                    <x-button class="w-100">{{ __('Save') }}</x-button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/userSettings.js') }}"></script>
</x-app-layout>

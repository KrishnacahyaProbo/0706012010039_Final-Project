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

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Pengaturan')
        </div>

        <div class="card">
            <form method="POST" id="pengiriman">
                @csrf

                <div class="d-grid gap-3">
                    <h3>Pengiriman</h3>

                    <div>
                        <label for="distance_between" class="form-label">{{ __('Jarak Maksimum') }}</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">km</span>
                            <input id="distance_between" type="number" name="distance_between" class="form-control"
                                value="{{ $delivery != null ? $delivery->distance_between : '' }}" required>
                        </div>
                    </div>
                    <div>
                        <label for="shipping_cost" class="form-label">{{ __('Ongkos Pengiriman') }}</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input id="shipping_cost" type="number" name="shipping_cost" class="form-control"
                                value = "{{ $delivery != null ? $delivery->shipping_cost : '' }}" required>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100" id="pengirimanBtn" type="button"
                        onclick="settingsDelivery()">{{ __('Save') }}</button>
                </div>
            </form>
        </div>

        <div class="card">
            <form method="POST" id="pesanan">
                @csrf

                <div class="d-grid gap-3">
                    <h3>Hari Konfirmasi Pesanan</h3>

                    <div>
                        <label for="confirmation_days"
                            class="form-label">{{ __('Batas Hari Terakhir Sebelum Tanggal Permintaan dan Pembatalan') }}</label>
                        <input id="confirmation_days" type="number" name="confirmation_days" class="form-control"
                            value="{{ $user_setting != null ? $user_setting->confirmation_days : '' }}" required>
                    </div>

                    <button class="btn btn-primary w-100" type="button"
                        onclick="userSettings()">{{ __('Save') }}</button>
                </div>
            </form>
        </div>

        <div class="card">
            <form method="POST" id="rekeningUser">
                @csrf

                <div class="d-grid gap-3">
                    <h3>Akun Rekening</h3>

                    <div>
                        <label for="bank_name" class="form-label">{{ __('Nama Bank') }}</label>
                        <input id="bank_name" type="text" name="bank_name" class="form-control"
                            value= "{{ $balance != null ? $balance->bank_name : '' }}" required>
                    </div>

                    <div>
                        <label for="account_number" class="form-label">{{ __('Nomor Rekening') }}</label>
                        <input id="account_number" type="number" name="account_number" class="form-control"
                            value=  "{{ $balance != null ? $balance->account_number : '' }}" required>
                    </div>

                    <div>
                        <label for="account_holder_name" class="form-label">{{ __('Nama Pemilik Rekening') }}</label>
                        <input id="account_holder_name" type="text" name="account_holder_name" class="form-control"
                            value="{{ $balance != null ? $balance->account_holder_name : '' }}" required>
                    </div>

                    <button class="btn btn-primary w-100" type="button"
                        onclick="balanceSettings()">{{ __('Save') }}</button>
                </div>
            </form>
        </div>

        <div class="card">
            <form method="POST" id="alamat">
                @csrf

                <div class="d-grid gap-3">
                    <h3>Alamat Pengiriman</h3>

                    <div>
                        <x-label for="address" value="{{ __('Alamat') }}" />
                        <x-input type="text" id="searchInput" class="form-control" placeholder="Cari Alamat" />
                        
                        <div id="addressDropdown"></div>
                    </div>

                    <x-input type="text" id="selectedAddress" value="" class="d-none" readonly />

                    <div>
                        <div id="map" class="rounded-1"></div>
                    </div>

                    <div class="d-none">
                        <div>
                            <x-label for="latitude" value="{{ __('Latitude') }}" />
                            <x-input name="latitude" id="latitude" type="text" autocomplete="off"
                                class="form-control" readonly />
                        </div>

                        <div>
                            <x-label for="longitude" value="{{ __('Longitude') }}" />
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

                    <button class="btn btn-primary w-100" type="button"
                        onclick="setAlamat()">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @section('js')
        <script src="{{ asset('js/userSettings.js') }}"></script>
    @endsection

</x-app-layout>

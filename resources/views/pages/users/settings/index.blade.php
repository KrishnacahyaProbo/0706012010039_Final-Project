@section('title', 'Pengaturan')

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

        @if (Auth::user()->hasRole('vendor'))
            @if ($delivery == null || $user_setting == null || $balance == null)
                <div class="alert alert-warning d-grid gap-3 text-center" role="alert">
                    <i class="bi bi-gear display-1"></i>
                    <span>Mohon mengisi seluruh pengaturan akun Anda terlebih dahulu.</span>
                </div>
            @endif
        @else
            @if ($user_setting == null || $balance == null)
                <div class="alert alert-warning d-grid gap-3 text-center" role="alert">
                    <i class="bi bi-gear display-1"></i>
                    <span>Mohon mengisi seluruh pengaturan akun Anda terlebih dahulu.</span>
                </div>
            @endif
        @endif

        @if (Auth::user()->hasRole('vendor'))
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="pengiriman">
                        @csrf

                        <div class="d-grid gap-3">
                            <h3>Pengiriman</h3>

                            <div>
                                <label for="distance_between" class="form-label">{{ __('Jarak Maksimum') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">km</span>
                                    <input id="distance_between" type="number" name="distance_between"
                                        class="form-control" min="1"
                                        value="{{ $delivery != null ? $delivery->distance_between : '' }}" required>
                                </div>
                            </div>
                            <div>
                                <label for="shipping_cost" class="form-label">{{ __('Ongkos Pengiriman') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input id="shipping_cost" type="number" name="shipping_cost" class="form-control"
                                        min="1" value = "{{ $delivery != null ? $delivery->shipping_cost : '' }}"
                                        required>
                                </div>
                            </div>

                            <button class="btn btn-primary w-100" id="pengirimanBtn" type="button"
                                onclick="deliverySetting()">{{ __('Kirim') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" id="pesanan">
                        @csrf

                        <div class="d-grid gap-3">
                            <h3>Hari Konfirmasi Pesanan</h3>

                            <div>
                                <label for="confirmation_days"
                                    class="form-label">{{ __('Batas Hari Terakhir Sebelum Tanggal Permintaan dan Pembatalan') }}</label>
                                <input id="confirmation_days" type="number" name="confirmation_days"
                                    class="form-control" min="1"
                                    value="{{ $user_setting != null ? $user_setting->confirmation_days : '' }}"
                                    required>
                            </div>

                            <button class="btn btn-primary w-100" type="button"
                                onclick="setPesanan()">{{ __('Kirim') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" id="about">
                        @csrf

                        <div class="d-grid gap-3">
                            <h3>Tentang Vendor</h3>

                            <div>
                                <label for="about_us" class="form-label">{{ __('Deskripsi Vendor') }}</label>
                                <textarea id="about_us" name="about_us" class="form-control" required>{{ $user_setting != null ? $user_setting->about_us : '' }}</textarea>
                            </div>

                            <button class="btn btn-primary w-100" type="button"
                                onclick="aboutSetting()">{{ __('Kirim') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" id="rekeningUser">
                    @csrf

                    <div class="d-grid gap-3">
                        <h3>Akun Rekening</h3>

                        <div>
                            <label for="bank_name" class="form-label">{{ __('Nama Bank') }}</label>
                            <select class="form-select" name="bank_name" id="bank_name" required>
                                @foreach ([
        'BANK UMUM PERSERO' => ['PT BANK RAKYAT INDONESIA (PERSERO) Tbk', 'PT BANK MANDIRI (PERSERO) Tbk', 'PT BANK NEGARA INDONESIA (PERSERO) Tbk', 'PT BANK TABUNGAN NEGARA (PERSERO) Tbk'],
        'BANK UMUM SWASTA NASIONAL' => [
            'PT BANK DANAMON INDONESIA Tbk',
            'PT BANK PERMATA Tbk',
            'PT BANK CENTRAL ASIA Tbk',
            'PT BANK MAYBANK INDONESIA Tbk',
            'PT BANK PAN INDONESIA Tbk',
            'PT BANK CIMB NIAGA Tbk',
            'PT BANK UOB INDONESIA',
            'PT BANK OCBC NISP Tbk',
            'PT BANK ARTHA GRAHA INTERNASIONAL Tbk',
            'PT BANK BUMI ARTA Tbk',
            'PT BANK HSBC INDONESIA',
            'PT BANK JTRUST INDONESIA Tbk',
            'PT BANK MAYAPADA INTERNATIONAL Tbk',
            'PT BANK OF INDIA INDONESIA Tbk',
            'PT BANK MUAMALAT INDONESIA Tbk',
            'PT BANK MESTIKA DHARMA Tbk',
            'PT BANK SHINHAN INDONESIA',
            'PT BANK SINARMAS Tbk',
            'PT BANK MASPION INDONESIA Tbk',
            'PT BANK GANESHA Tbk',
            'PT BANK ICBC INDONESIA',
            'PT BANK QNB INDONESIA Tbk',
            'PT BANK WOORI SAUDARA INDONESIA 1906 Tbk',
            'PT BANK MEGA Tbk',
            'PT BANK SYARIAH INDONESIA Tbk',
            'PT BANK KB BUKOPIN Tbk',
            'PT BANK KEB HANA INDONESIA',
            'PT BANK MNC INTERNASIONAL Tbk',
            'PT BANK RAYA INDONESIA Tbk',
            'PT BANK SBI INDONESIA',
            'PT BANK MEGA SYARIAH',
            'PT BANK INDEX SELINDO',
            'PT BANK HIBANK INDONESIA',
            'PT BANK CHINA CONSTRUCTION BANK INDONESIA Tbk',
            'PT BANK DBS INDONESIA',
            'PT BANK RESONA PERDANIA',
            'PT BANK MIZUHO INDONESIA',
            'PT BANK CAPITAL INDONESIA Tbk',
            'PT BANK BNP PARIBAS INDONESIA',
            'PT BANK ANZ INDONESIA',
            'PT BANK IBK INDONESIA Tbk',
            'PT BANK ALADIN SYARIAH Tbk',
            'PT BANK CTBC INDONESIA',
            'PT BANK COMMONWEALTH',
            'PT BANK TABUNGAN PENSIUNAN NASIONAL, Tbk',
            'PT BANK VICTORIA SYARIAH',
            'PT BANK JABAR BANTEN SYARIAH',
            'PT KROM BANK INDONESIA Tbk',
            'PT BANK JASA JAKARTA',
            'PT BANK NEO COMMERCE Tbk',
            'PT BANK DIGITAL BCA',
            'PT BANK NATIONALNOBU Tbk',
            'PT BANK INA PERDANA Tbk',
            'PT BANK PANIN DUBAI SYARIAH Tbk',
            'PT BANK KB BUKOPIN SYARIAH',
            'PT BANK SAHABAT SAMPOERNA',
            'PT BANK OKE INDONESIA Tbk',
            'PT BANK AMAR INDONESIA',
            'PT BANK SEABANK INDONESIA',
            'PT BANK BCA SYARIAH',
            'PT BANK JAGO TBK',
            'PT BANK TABUNGAN PENSIUNAN NASIONAL SYARIAH Tbk',
            'PT BANK MULTIARTA SENTOSA',
            'PT SUPER BANK INDONESIA',
            'PT BANK MANDIRI TASPEN',
            'PT BANK VICTORIA INTERNATIONAL Tbk',
            'PT ALLO BANK INDONESIA Tbk',
            'PT BANK NANO SYARIAH',
        ],
        'BANK PEMBANGUNAN DAERAH' => ['PT BPD JAWA BARAT DAN BANTEN Tbk', 'PT BPD DKI', 'PT BPD DAERAH ISTIMEWA YOGYAKARTA', 'PT BPD JAWA TENGAH', 'PT BPD JAWA TIMUR Tbk', 'PT BPD JAMBI', 'PT BANK ACEH SYARIAH', 'PT BPD SUMATERA UTARA', 'PT BANK NAGARI', 'PT BPD RIAU KEPRI SYARIAH', 'PT BPD SUMATERA SELATAN DAN BANGKA BELITUNG', 'PT BPD LAMPUNG', 'PT BPD KALIMANTAN SELATAN', 'PT BPD KALIMANTAN BARAT', 'PT BPD KALIMANTAN TIMUR DAN KALIMANTAN UTARA', 'PT BPD KALIMANTAN TENGAH', 'PT BPD SULAWESI SELATAN DAN SULAWESI BARAT', 'PT BPD SULAWESI UTARA DAN GORONTALO', 'PT BANK NTB SYARIAH', 'PT BPD BALI', 'PT BPD NUSA TENGGARA TIMUR', 'PT BPD MALUKU DAN MALUKU UTARA', 'PT BPD PAPUA', 'PT BPD BENGKULU', 'PT BPD SULAWESI TENGAH', 'PT BPD SULAWESI TENGGARA', 'PT BPD BANTEN Tbk'],
        'KANTOR CABANG BANK YANG BERKEDUDUKAN DI LUAR NEGERI' => ['CITIBANK, N.A.', 'JP MORGAN CHASE BANK, NA', 'BANK OF AMERICA, N.A', 'MUFG BANK, LTD', 'STANDARD CHARTERED BANK', 'DEUTSCHE BANK AG', 'BANK OF CHINA (HONG KONG) LIMITED'],
    ] as $optgroupLabel => $banks)
                                    <optgroup label="{{ $optgroupLabel }}">
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank }}"
                                                {{ $balance != null && $balance->bank_name == $bank ? 'selected' : '' }}>
                                                {{ $bank }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="account_number" class="form-label">{{ __('Nomor Rekening') }}</label>
                            <input id="account_number" type="number" name="account_number" class="form-control"
                                value="{{ $balance != null ? $balance->account_number : '' }}" required>
                        </div>

                        <div>
                            <label for="account_holder_name"
                                class="form-label">{{ __('Nama Pemilik Rekening') }}</label>
                            <input id="account_holder_name" type="text" name="account_holder_name"
                                class="form-control"
                                value="{{ $balance != null ? $balance->account_holder_name : '' }}" required>
                        </div>

                        <button class="btn btn-primary w-100" type="button"
                            onclick="balanceSetting()">{{ __('Kirim') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" id="alamat">
                    @csrf

                    <div class="d-grid gap-3">
                        <h3>Alamat Pengiriman</h3>

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

                        <hr class="my-0">

                        <div>
                            <strong>{{ Auth::user()->hasRole('vendor') ? 'Lokasi asal pengiriman: ' : 'Lokasi tujuan pengiriman: ' }}</strong>
                            <span>{{ $user_setting != null ? $user_setting->address : '-' }}</span>
                        </div>

                        <button class="btn btn-primary w-100" type="button"
                            onclick="setAlamat()">{{ __('Kirim') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('js')
        <script src="{{ asset('js/userSettings.js') }}"></script>
    @endsection
</x-app-layout>

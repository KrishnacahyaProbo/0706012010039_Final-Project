@section('title', 'Rumah Katering')

<x-guest-layout>
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

    @if (Auth::check())
        @if (Auth::user()->hasRole('vendor') &&
                ($authDelivery === null || $confirmationDays->confirmation_days === null || $balance === null))
            @include('pages.users.include.settingModal')
        @endif
        @if (Auth::user()->hasRole('customer') && $balance === null)
            @include('pages.users.include.settingModal')
        @endif
    @endif

    <div class="d-grid gap-5">
        @php
            $highlights = [
                [
                    'cta_id' => 'home_customer',
                    'cta_title' => 'Mau pesan Katering apa?',
                    'cta_button' => 'Eksplor Vendor',
                    'feature_title' => 'Mudahnya Berbelanja Katering',
                    'feature_items' => [
                        [
                            'feature_icon' => 'shop',
                            'feature_color' => 'primary',
                            'feature_text' => 'Berbagai pilihan Vendor Katering.',
                        ],
                        [
                            'feature_icon' => 'journal-text',
                            'feature_color' => 'primary',
                            'feature_text' => 'Menu yang informatif dan interaktif.',
                        ],
                        [
                            'feature_icon' => 'cart-check',
                            'feature_color' => 'primary',
                            'feature_text' => 'Pemesanan praktis yang terjadwal.',
                        ],
                    ],
                ],
                [
                    'cta_id' => 'home_vendor',
                    'cta_title' => 'Bergabung menjadi Vendor',
                    'cta_button' => 'Jual Produk',
                    'feature_title' => 'Terstrukturnya Pengelolaan Katering',
                    'feature_items' => [
                        [
                            'feature_icon' => 'calendar3',
                            'feature_color' => 'success',
                            'feature_text' => 'Jadwalkan penjualan menu.',
                        ],
                        [
                            'feature_icon' => 'ui-checks',
                            'feature_color' => 'success',
                            'feature_text' => 'Rekapitulasi pesanan untuk persiapan yang lebih matang.',
                        ],
                        [
                            'feature_icon' => 'chat-left-text',
                            'feature_color' => 'success',
                            'feature_text' => 'Pantau testimoni untuk evaluasi berkelanjutan.',
                        ],
                    ],
                ],
            ];
        @endphp

        @foreach ($highlights as $highlight)
            <div class="my-3">
                <div class="d-grid gap-4">
                    <div class="d-grid rounded-3 p-md-5 gap-4 p-4 text-white shadow-sm" id={{ $highlight['cta_id'] }}>
                        <div>
                            <h1 class="display-4">{{ $highlight['cta_title'] }}</h1>
                        </div>

                        <div>
                            <a href="{{ $highlight['cta_id'] === 'home_customer' ? route('vendor.index') : route('menu.index') }}"
                                class="btn btn-lg btn-primary">{{ $highlight['cta_button'] }}</a>
                        </div>
                    </div>

                    <div class="d-grid gap-1">
                        <h3>{{ $highlight['feature_title'] }}</h3>

                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @foreach ($highlight['feature_items'] as $item)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div
                                                class="bg-{{ $item['feature_color'] }}-subtle d-inline-block rounded-1 px-3 py-2">
                                                <i
                                                    class="bi bi-{{ $item['feature_icon'] }} fs-3 text-{{ $item['feature_color'] }}-emphasis"></i>
                                            </div>
                                            <p class="mb-0 mt-3">{{ $item['feature_text'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card rounded-4 border-0 shadow-sm" id="home_panduan_penggunaan">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <img src="{{ url('images/assets/home/manual_book.svg') }}" alt=""
                            class="d-none d-md-block p-5">
                    </div>
                    <div class="col-md align-self-center">
                        <h3 class="card-title">Panduan Penggunaan</h3>
                        <p class="card-text"><strong>Rumah Katering</strong> merupakan aplikasi Marketplace berbasis web
                            yang menghubungkan vendor (penjual) dengan pelanggan (pembeli) untuk pemesanan dan
                            pembayaran Katering harian.
                        </p>
                        <a href="{{ url('/assets/pdf/Panduan Penggunaan Web Rumah Katering.pdf') }}" target="_blank"
                            rel="noopener noreferrer">
                            <x-button>Pelajari Lebih Lanjut</x-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div>
        <figure class="text-center">
            <i class="bi bi-house-door fs-1"></i>

            <blockquote class="blockquote">
                <q>Tempatnya Para Penyedia dan Penikmat Katering Singgah</q>
            </blockquote>

            <figcaption class="blockquote-footer">Rumah Katering, 2024</figcaption>
        </figure>
    </div>
    </div>

    @section('js')
        <script src="{{ asset('js/setting.js') }}"></script>
    @endsection
</x-guest-layout>

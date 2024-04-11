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

    <div class="d-grid gap-5">
        <div>
            @section('page_title', 'Rumah Katering')
        </div>

        @php
            $sections = [
                [
                    'title' => 'Terstrukturnya Pengelolaan Katering Bagi Penjual',
                    'items' => [
                        ['icon' => 'calendar3', 'color' => 'primary', 'text' => 'Jadwalkan penjualan menu.'],
                        ['icon' => 'ui-checks', 'color' => 'success', 'text' => 'Persiapkan rekapitulasi pesanan.'],
                        [
                            'icon' => 'chat-left-text',
                            'color' => 'warning',
                            'text' => 'Pantau testimoni untuk evaluasi berkelanjutan.',
                        ],
                    ],
                ],
                [
                    'title' => 'Mudahnya Berbelanja Katering Bagi Pelanggan',
                    'items' => [
                        ['icon' => 'shop', 'color' => 'primary', 'text' => 'Berbagai pilihan Vendor Katering.'],
                        [
                            'icon' => 'journal-text',
                            'color' => 'success',
                            'text' => 'Menu yang informatif dan interaktif.',
                        ],
                        ['icon' => 'cart-check', 'color' => 'warning', 'text' => 'Pemesanan praktis yang terjadwal.'],
                    ],
                ],
            ];
        @endphp

        <div class="d-grid gap-4">
            @foreach ($sections as $section)
                <div class="d-grid gap-1">
                    <h3>{{ $section['title'] }}</h3>

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        @foreach ($section['items'] as $item)
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body p-0">
                                        <div class="bg-{{ $item['color'] }}-subtle d-inline-block rounded-1 px-3 py-2">
                                            <i
                                                class="bi bi-{{ $item['icon'] }} fs-3 text-{{ $item['color'] }}-emphasis"></i>
                                        </div>
                                        <p class="mb-0 mt-3">{{ $item['text'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <hr>

        <figure class="text-center">
            <i class="bi bi-house-door fs-1"></i>

            <blockquote class="blockquote">
                <q>Tempatnya Para Penjual dan Pelanggan Katering Singgah</q>
            </blockquote>

            <figcaption class="blockquote-footer">Rumah Katering, 2024</figcaption>
        </figure>
    </div>
</x-guest-layout>

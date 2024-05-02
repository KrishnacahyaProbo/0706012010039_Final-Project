@section('title', 'Checkout')

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
            @section('page_title', 'Konfirmasi Pembayaran')
        </div>

        {{-- <div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="d-grid">
                            <span class="text-secondary">Alamat Pengiriman</span>
                            <strong>{{ Auth::user()->address }}</strong>
                        </div>

                        <button class="btn text-primary border-0 p-0">
                            <a href="{{ route('setting.index') }}">Ubah</a>
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row g-3">
            <div class="col-lg-8 d-grid gap-3">
                @php
                    $temp = [];
                @endphp
                @foreach ($cart as $item)
                    @php
                        $index = -1;
                        foreach ($temp as $keys => $detail) {
                            if ($detail['name'] == $item['menu']['vendor']['name']) {
                                $index = $keys;
                            }
                        }
                        if ($index == -1) {
                            array_push($temp, [
                                'name' => $item['menu']['vendor']['name'],
                                'price' => 0,
                                'items' => [],
                            ]);
                            $index = count($temp) - 1;
                        }
                        $temp[$index]['items'][] = $item;
                    @endphp
                @endforeach

                @foreach ($temp as $vendor)
                    @php
                        $ringkasanBelanja = $loop->iteration - 1;
                    @endphp
                    <div class="card" id="deleteCartItem{{ $item->id }}">
                        <div class="card-header">
                            <strong>{{ $vendor['name'] }}</strong>
                        </div>
                        <div class="card-body d-grid gap-4">
                            @foreach ($vendor['items'] as $item)
                                @php
                                    $indexItem = $loop->iteration - 1;
                                @endphp
                                <div class="d-grid row-per-item gap-3">
                                    <div>
                                        <div>
                                            <span
                                                class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border">{{ date('l, j F Y', strtotime($item->schedule_date)) }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <div>
                                            <div
                                                class="d-grid d-md-flex justify-content-between align-items-center row-item">
                                                <div class="d-grid d-md-flex align-items-center gap-1">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <img src="{{ Str::startsWith($item->menu->image, 'http') ? $item->menu->image : asset('menu/' . $item->menu->image) }}"
                                                                alt="" class="rounded-1 menu-photo">
                                                        </div>

                                                        <div class="col-md-9 d-grid gap-2">
                                                            <h3>{{ $item->menu->menu_name }}</h3>
                                                            <div class="d-flex gap-2">
                                                                <span>
                                                                    <span
                                                                        class="badge rounded-pill text-light-emphasis bg-light-subtle border-light-subtle border">
                                                                        {{ $item->portion }}</span>
                                                                </span>
                                                                <span>{!! $item->menu->type === 'spicy'
                                                                    ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pedas</span>'
                                                                    : '<span class="badge rounded-pill text-primary-emphasis bg-primary-subtle border border-primary-subtle">Tidak Pedas</span>' !!}</span>
                                                            </div>
                                                            <small class="text-secondary">
                                                                <pre class="mb-0">{{ $item->menu->description }}</pre>
                                                            </small>
                                                            @foreach ($item->menu->menuDetail as $detail)
                                                                @if ($detail->size == $item->portion)
                                                                    @php
                                                                        $temp[$ringkasanBelanja]['price'] +=
                                                                            $detail->price * $item->quantity;
                                                                    @endphp
                                                                    <h5
                                                                        id="newHarga{{ $ringkasanBelanja . $indexItem }}">
                                                                        Rp{{ number_format($detail->price, 0, ',', '.') }}
                                                                        x {{ $item->quantity }} pcs
                                                                    </h5>
                                                                    <input type="hidden" name="cart_menu_id"
                                                                        id="cart_menu_id" value="{{ $item->id }}">
                                                                    <input type="hidden" name="price" id="price"
                                                                        value="{{ $detail->price }}">
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="text-secondary">Kredit</span>
                            </div>
                            <div class="col px-1">
                                <span class="badge rounded-pill nominal_background">
                                    <h6 class="mb-0 px-2 py-1">
                                        Rp{{ number_format($balance->credit ?? '0', 0, ',', '.') }}</h6>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <h3>Ringkasan Belanja</h3>
                            <ul class="list-unstyled">
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Total {{ $item->count() }} Produk: </span>
                                        <span class="text-break">
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach ($temp as $collection)
                                                @php
                                                    $total += $collection['price'];
                                                @endphp
                                            @endforeach
                                            <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
                                        </span>
                                    </div>
                                </li>
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Total Ongkos Kirim: </span>
                                        <span class="text-break">
                                            <strong>Rp{{ number_format($shipping_costs, 0, ',', '.') }}</strong>
                                        </span>
                                    </div>
                                </li>
                            </ul>

                            <hr class="my-0">

                            <div class="row align-items-center gap-3">
                                <div class="col">
                                    <span class="text-secondary">Total Pembayaran: </span>
                                    <span class="fs-5 text-break">
                                        <strong>Rp{{ number_format($total + $shipping_costs, 0, ',', '.') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <x-button>Pay</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

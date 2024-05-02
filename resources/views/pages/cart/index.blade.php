@section('title', 'Cart')

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
            @section('page_title', 'Keranjang Belanja')
        </div>

        @if (count($cart) == 0)
            <div class="alert alert-info">
                <p>Keranjang Belanja masih kosong. Silakan pilih menu yang ingin dimasukkan Keranjang Belanja.</p>
                <a href="{{ route('vendor.index') }}" class="btn btn-primary">Eksplor Vendor</a>
            </div>
        @endif

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
                        <div class="card-body d-grid gap-3">
                            @foreach ($vendor['items'] as $item)
                                @php
                                    $indexItem = $loop->iteration - 1;
                                @endphp
                                <div class="d-grid row-per-item note gap-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span
                                                class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border">{{ date('l, j F Y', strtotime($item->schedule_date)) }}</span>
                                        </div>
                                        <div>
                                            <x-secondary-button data-bs-toggle="collapse"
                                                data-bs-target="#collapseCatatanPesanan" aria-expanded="false"
                                                aria-controls="collapseCatatanPesanan">Catatan</x-secondary-button>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapseCatatanPesanan">
                                        <x-label for="note" value="{{ __('Catatan Pesanan') }}" />
                                        <textarea id="note" name="note" class="form-control" ringkasanBelanja="{{ $ringkasanBelanja }}"
                                            indexItem="{{ $indexItem }}">{{ $item->note }}</textarea>
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
                                                            <span>{!! $item->menu->type === 'spicy'
                                                                ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pedas</span>'
                                                                : '<span class="badge rounded-pill text-primary-emphasis bg-primary-subtle border border-primary-subtle">Tidak Pedas</span>' !!}</span>
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
                                                                        id="newPrice{{ $ringkasanBelanja . $indexItem }}">
                                                                        Rp{{ number_format($detail->price, 0, ',', '.') }}/pcs
                                                                    </h5>
                                                                    <input type="hidden" name="cart_menu_id"
                                                                        id="cart_menu_id" value="{{ $item->id }}">
                                                                    <input type="hidden" name="price" id="price"
                                                                        value="{{ $detail->price }}">
                                                                    <input type="hidden" name="note" id="note"
                                                                        value="{{ $detail->note }}">
                                                                @endif
                                                            @endforeach
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span>Porsi</span>
                                                                @foreach ($item->menu->menuDetail as $detail)
                                                                    @php
                                                                        $active =
                                                                            $detail->size == $item->portion
                                                                                ? 'portion'
                                                                                : '';
                                                                    @endphp
                                                                    <button
                                                                        class="btn btn-outline-primary rounded-pill {{ $active }} btn-portion px-3"
                                                                        ringkasanBelanja="{{ $ringkasanBelanja }}"
                                                                        indexItem="{{ $indexItem }}">{{ $detail->size }}</button>
                                                                @endforeach
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span>Kuantitas</span>
                                                                <div
                                                                    class="d-flex align-items-center border-secondary rounded border">
                                                                    <div class="input-group text-center"
                                                                        id="quantity_counter">
                                                                        <button
                                                                            class="input-group-text btn btn-decrement border-0">
                                                                            <i class="bi bi-dash-lg text-primary"></i>
                                                                        </button>
                                                                        <input type="text" name="quantity"
                                                                            value="{{ $item->quantity }}"
                                                                            class="qty-input form-control text-center"
                                                                            id="quantity" readonly>
                                                                        <button
                                                                            class="input-group-text btn btn-increment border-0">
                                                                            <i class="bi bi-plus-lg text-primary"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="my-md-1 my-2 text-center">
                                                    <button class="btn border-0 p-0" title="Remove from Cart"
                                                        onclick="destroy({{ $item->id }})">
                                                        <i class="bi bi-trash3 text-danger d-none d-md-block"></i>
                                                        <strong class="text-danger d-md-none">Remove from Cart</strong>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($loop->remaining > 0)
                                    <hr class="my-0">
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @if (count($cart) != 0)
                <div class="col-lg-4">
                    <div class="sticky-md-top">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-3">
                                    <h3>Ringkasan Belanja</h3>
                                    <div class="table-responsive">
                                        <table class="table-striped table-hover table-borderless table">
                                            <thead>
                                                <tr>
                                                    <th>Vendor</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($temp as $item)
                                                    <tr>
                                                        <td>{{ $item['name'] }}</td>
                                                        <td>
                                                            <strong>Rp{{ number_format($item['price'], 0, ',', '.') }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row align-items-center gap-3">
                                        <div class="col">
                                            <span class="fs-5 text-secondary">Total: </span>
                                            <span class="fs-5 text-break">
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
                                    </div>

                                    <form action="{{ route('cart.checkout') }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">Checkout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @section('js')
        <script>
            var data = @json($temp);
        </script>
        <script src="{{ asset('/js/detailVendor.js') }}"></script>
        <script src="{{ asset('/js/cart.js') }}"></script>
    @endsection
</x-app-layout>

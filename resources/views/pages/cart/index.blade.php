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

        <div class="row g-3">
            <div class="col-lg-8 d-grid gap-3">
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div for="select_all" class="d-flex gap-2">
                                    <x-checkbox id="select_all" name="select_all" />
                                    <label class="form-check-label" for="select_all">
                                        <span><strong>Pilih Semua ({{ $cart->count() }})</strong></span>
                                    </label>
                                </div>

                                <button class="btn text-danger border-0 p-0">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($cart as $item)
                    <div class="card">
                        <div class="card-header">
                            <div for="selected_vendor" class="d-flex gap-2">
                                <x-checkbox id="selected_vendor" name="selected_vendor" />
                                <label class="form-check-label" for="selected_vendor">
                                    <strong>Nama Vendor</strong>
                                </label>
                            </div>
                        </div>
                        <div class="card-body d-grid gap-3">
                            <div>
                                <span
                                    class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border">{{ date('l, j F Y') }}</span>
                            </div>

                            <div>
                                <div class="d-grid d-md-flex justify-content-between align-items-center">
                                    <div class="d-grid d-md-flex align-items-center gap-2">
                                        <x-checkbox id="selected_vendor" name="selected_vendor" />
                                        <div class="d-grid d-md-flex gap-3">
                                            <img src={{ $item->menu->image }} alt="" class="w-25 rounded-1">
                                            <div class="d-grid gap-2">
                                                <h3>{{ $item->menu->menu_name }}</h3>
                                                <small class="text-secondary">{{ $item->menu->description }}</small>
                                                <h5>Harga/pcs</h5>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span>Porsi</span>
                                                    <div>
                                                        <button
                                                            class="btn btn-outline-primary rounded-pill mx-1 px-3">Small</button>
                                                        <button
                                                            class="btn btn-outline-primary rounded-pill mx-1 px-3">Medium</button>
                                                        <button
                                                            class="btn btn-outline-primary rounded-pill mx-1 px-3">Large</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span>Kuantitas</span>
                                                    <div
                                                        class="d-flex align-items-center border-secondary rounded border">
                                                        <div class="input-group text-center" id="quantity_counter">
                                                            <button class="input-group-text btn decrement-btn border-0">
                                                                <i class="bi bi-dash-lg text-primary"></i>
                                                            </button>
                                                            <input type="text" name="quantity" value="1"
                                                                class="qty-input form-control text-center" readonly>
                                                            <button class="input-group-text btn increment-btn border-0">
                                                                <i class="bi bi-plus-lg text-primary"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-1">
                                        <button class="btn border-0 p-0" title="Remove from Cart" onclick="destroy()">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <h3>Ringkasan Belanja</h3>
                            <ul class="list-unstyled">
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Nama Vendor: </span>
                                        <span class="text-break">Nominal</span>
                                    </div>
                                </li>
                            </ul>

                            <hr class="my-0">

                            <div class="row align-items-center gap-3">
                                <div class="col">
                                    <span class="text-secondary">Total: </span>
                                    <span class="fs-5 text-break"><strong>Nominal</strong></span>
                                </div>
                            </div>

                            <x-button>Checkout</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script src="{{ asset('/js/detailVendor.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.increment-btn').click(function(e) {
                    e.preventDefault();

                    var $input = $(this).closest('.d-flex').find('.qty-input');
                    var value = parseInt($input.val(), 10);
                    value = isNaN(value) ? 0 : value;
                    if (value < 999) {
                        $input.val(value + 1);
                    }
                });

                $('.decrement-btn').click(function(e) {
                    e.preventDefault();

                    var $input = $(this).closest('.d-flex').find('.qty-input');
                    var value = parseInt($input.val(), 10);
                    value = isNaN(value) ? 0 : value;
                    if (value > 1) {
                        $input.val(value - 1);
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>

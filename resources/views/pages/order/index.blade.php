@section('title', 'Order')

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

    @if (Auth::user()->hasRole('vendor'))
        <div class="d-grid gap-3">
            <div>
                @section('page_title', 'Kelola Pesanan')
            </div>

            <div class="d-flex ms-auto gap-2">
                <div>
                    <input type="date" name="schedule_date" id="schedule_date" class="form-control">
                </div>
                <div>
                    <select class="form-select" aria-label="Status select" id="vendor_status">
                        <option selected value="customer_paid">Pesanan</option>
                        <option value="vendor_packing">Dikemas</option>
                        <option value="vendor_delivering">Dikirim</option>
                        <option value="customer_received">Diterima</option>
                        <option value="customer_complain">Komplain</option>
                    </select>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="rekapitulasi"></div>

            <div class="table-responsive">
                <table id="orderVendorTable" class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Nama Menu</th>
                            <th>Ukuran Porsi</th>
                            <th>Kuantitas</th>
                            <th>Pelanggan</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        @include('pages.order.include.incomingOrderDetailModal')
        @include('pages.order.include.viewTestimonyModal')
    @else
        <div class="d-grid gap-3">
            <div>
                @section('page_title', 'Riwayat Pesanan')
            </div>

            <div class="d-flex ms-auto">
                <select class="form-select" aria-label="Status select" id="customer_status">
                    <option selected value="customer_paid">Lunas</option>
                    <option value="customer_canceled">Dibatalkan</option>
                    <option value="vendor_packing">Dikemas</option>
                    <option value="vendor_delivering">Dikirim</option>
                    <option value="customer_received">Diterima</option>
                    <option value="customer_complain">Komplain</option>
                </select>
            </div>

            <div class="table-responsive">
                <table id="orderCustomerTable" class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Nama Menu</th>
                            <th>Ukuran Porsi</th>
                            <th>Harga Item</th>
                            <th>Kuantitas</th>
                            <th>Total Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        @include('pages.order.include.requestOrderDetailModal')
        @include('pages.testimony.include.addTestimonyModal')
        @include('pages.order.include.refundReasonModal')
    @endif

    <script>
        const _APP_URL = {!! '"' . env('APP_URL') . '"' !!}
    </script>
    <script src="{{ asset('/js/order.js') }}"></script>
    <script src="{{ asset('/js/testimony.js') }}"></script>
</x-app-layout>

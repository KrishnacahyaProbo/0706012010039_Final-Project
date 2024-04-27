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
                    <x-secondary-button>Download Report</x-secondary-button>
                </div>

                <div>
                    <select class="form-select" aria-label="Date select">
                        <option selected>{{ date('j F Y') }}</option>
                        <option value="">Tanggal jadwal penjualan</option>
                    </select>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @for ($i = 0; $i < 3; $i++)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-grid gap-3">
                                    <h3>Total Kuantitas</h3>
                                    <span class="text-secondary lead">Nama Menu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="table-responsive">
                <table id="menuTable" class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Nama Menu</th>
                            <th>Ukuran Porsi</th>
                            <th>Kuantitas</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tbody></tbody> --}}
                    <tbody>
                        <tr>
                            <td>Pelanggan</td>
                            <td>Nama Menu</td>
                            <td>Ukuran Porsi</td>
                            <td>Harga</td>
                            <td>Alamat</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success" title="Process Order"
                                        onclick="alert('Yakin ingin memproses pesanan?')">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Reject Order"
                                        onclick="alert('Yakin ingin menolak pesanan?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="d-grid gap-3">
            <div>
                @section('page_title', 'Riwayat Pesanan')
            </div>

            <div class="d-flex ms-auto">
                <select class="form-select" aria-label="Date select">
                    <option selected>Belum Bayar</option>
                    <option value="">Dikirim</option>
                    <option value="">Diterima</option>
                </select>
            </div>

            <div class="table-responsive">
                <table id="menuTable" class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Nama Menu</th>
                            <th>Ukuran Porsi</th>
                            <th>Kuantitas</th>
                            <th>Total Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tbody></tbody> --}}
                    <tbody>
                        <tr>
                            <td>Tanggal</td>
                            <td>Vendor</td>
                            <td>Nama Menu</td>
                            <td>Ukuran Porsi</td>
                            <td>Kuantitas</td>
                            <td>Total Pembayaran</td>
                            <td>
                                <button class="btn btn-outline-danger" title="Cancel Order"
                                    onclick="alert('Yakin ingin membatalkan pesanan?')">
                                    Cancel Order
                                </button>
                                <button class="btn btn-success" title="Accept Order"
                                    onclick="alert('Yakin telah menerima pesanan sesuai dengan kondisi yang diinginkan?')">
                                    Accept Order
                                </button>
                                <button class="btn btn-primary" title="Add Testimony">
                                    Add Testimony
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>

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

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Kelola Pesanan')
        </div>

        <div class="d-flex ms-auto gap-2">
            <div>
                <x-secondary-button>Download Report</x-secondary-button>
            </div>

            <div>
                <x-dropdown>Add Menu</x-dropdown>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @for ($i = 0; $i < 3; $i++)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body p-0">
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
                                <button class="btn btn-danger" title="Reject Order"
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
</x-app-layout>

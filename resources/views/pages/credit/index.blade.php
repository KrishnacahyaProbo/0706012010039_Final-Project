@section('title', 'Credit')

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
        @if (Auth::user()->hasRole('vendor'))
            <div>
                @section('page_title', 'Cairkan Kredit')
            </div>

            @if (!$balance || $balance->credit <= 0)
                <x-button class="d-flex ms-auto" disabled>Request Cash out</x-button>
            @else
                <x-button class="d-flex ms-auto" data-bs-toggle="modal" data-bs-target="#cashOutForm">Request Cash
                    out</x-button>
            @endif

            @include('pages.credit.include.cashOutModal')

            <div class="row row-cols-1 row-cols-sm-2 g-3">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <span class="text-secondary">Kredit</span>
                                <div>
                                    <span class="badge rounded-pill nominal_background">
                                        <h3 class="mb-0 px-2 py-1">
                                            Rp{{ number_format($balance->credit ?? '0', 0, ',', '.') }}</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <h3>{{ $balance->bank_name ?? '-' }} {{ $balance->account_number ?? '-' }}</h3>
                                <span class="text-secondary lead">a.n.
                                    {{ $balance->account_holder_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex ms-auto">
                <select class="form-select" aria-label="Category select" id="vendor_category">
                    <option selected value="all_category">Semua</option>
                    <option value="vendor_income">Penjualan</option>
                    <option value="vendor_outcome">Cash out</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th class="col-0 text-center">#</th>
                            <th class="col-4">Nominal</th>
                            <th class="col-4">Kategori</th>
                            <th class="col-4">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="vendor_category_value"></tbody>
                    {{-- <tbody>
                        @if ($balance_history->isEmpty())
                            <tr>
                                <td colspan="3">Belum ada riwayat.</td>
                            </tr>
                        @else
                            @foreach ($balance_history as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>Rp{{ number_format($item->credit, 0, ',', '.') }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody> --}}
                </table>
            </div>
        @else
            <div>
                @section('page_title', 'Isi Ulang Kredit')
            </div>

            <x-button class="d-flex ms-auto" data-bs-toggle="modal" data-bs-target="#topUpForm">Top up</x-button>

            @include('pages.credit.include.topUpModal')

            <div class="row row-cols-1 row-cols-sm-2 g-3">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <span class="text-secondary">Kredit</span>
                                <div>
                                    <span class="badge rounded-pill nominal_background">
                                        <h3 class="mb-0 px-2 py-1">
                                            Rp{{ number_format($balance->credit ?? '0', 0, ',', '.') }}</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <h3>{{ $balance->bank_name ?? '-' }} {{ $balance->account_number ?? '-' }}</h3>
                                <span class="text-secondary lead">a.n.
                                    {{ $balance->account_holder_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex ms-auto">
                <select class="form-select" aria-label="Category select" id="customer_category">
                    <option selected value="all_category">Semua</option>
                    <option value="customer_income">Top up</option>
                    <option value="customer_outcome">Pembelian</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th class="col-0 text-center">#</th>
                            <th class="col-4">Nominal</th>
                            <th class="col-3">Kategori</th>
                            <th class="col-3">Timestamp</th>
                            <th class="col-0">Bukti Top Up</th>
                        </tr>
                    </thead>
                    <tbody id="customer_category_value"></tbody>
                </table>
            </div>
        @endif
    </div>

    @section('js')
        <script>
            var balanceNominal = {!! json_encode($balance->credit ?? 0) !!}
        </script>
        <script src="{{ asset('/js/vendorCredit.js') }}"></script>
        <script src="{{ asset('/js/customerCredit.js') }}"></script>
        <script src="{{ asset('/js/formatRupiah.js') }}"></script>
    @endsection
</x-app-layout>

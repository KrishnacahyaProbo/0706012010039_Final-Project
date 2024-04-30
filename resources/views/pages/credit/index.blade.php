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
                <x-button class="d-flex ms-auto" data-bs-toggle="modal" data-bs-target="#mdlForm">Request Cash
                    out</x-button>
            @endif

            <div class="modal fade" id="mdlForm" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="mdlFormTitle">Pencairan Kredit</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="mdlFormContent">
                            <form method="POST" action="/credits/cash-out">
                                @csrf

                                <div class="d-grid gap-3">
                                    <div>
                                        <x-label for="credit" value="{{ __('Nominal') }}" />
                                        <x-input id="credit" type="number" name="credit" required />
                                    </div>

                                    <x-button>{{ __('Save') }}</x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 g-3">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <span class="text-secondary">Kredit</span>
                                <div>
                                    <span class="badge rounded-pill nominal_background">
                                        <h3 class="mb-0 px-2 py-1">Rp{{ $balance->credit ?? '0' }}</h3>
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

            <div class="table-responsive">
                <table class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Nominal</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($balance_history->isEmpty())
                            <tr>
                                <td colspan="2">No data</td>
                            </tr>
                        @else
                            @foreach ($balance_history as $item)
                                <tr>
                                    <td>Rp{{ $item->credit }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div>
                @section('page_title', 'Isi Ulang Kredit')
            </div>

            <x-button class="d-flex ms-auto" data-bs-toggle="modal" data-bs-target="#mdlForm">Top up</x-button>

            <div class="modal fade" id="mdlForm" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="mdlFormTitle">Isi Ulang Kredit</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="mdlFormContent">
                            <form method="POST" action="/credits/top-up" enctype="multipart/form-data">
                                @csrf

                                <div class="d-grid gap-3">
                                    <div>
                                        <x-label for="credit" value="{{ __('Nominal') }}" />
                                        <x-input id="credit" type="number" name="credit" required />
                                    </div>

                                    <div>
                                        <x-label for="transaction_proof" value="{{ __('Foto Bukti Top Up') }}" />
                                        <input type="file" class="form-control" id="transaction_proof"
                                            name="transaction_proof" required>
                                    </div>

                                    <x-button>{{ __('Save') }}</x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 g-3">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <span class="text-secondary">Kredit</span>
                                <div>
                                    <span class="badge rounded-pill nominal_background">
                                        <h3 class="mb-0 px-2 py-1">Rp{{ $balance->credit ?? '0' }}</h3>
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

            <div class="table-responsive">
                <table class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Nominal</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($balance_history->isEmpty())
                            <tr>
                                <td colspan="2">No data</td>
                            </tr>
                        @else
                            @foreach ($balance_history as $item)
                                <tr>
                                    <td>Rp{{ $item->credit }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>

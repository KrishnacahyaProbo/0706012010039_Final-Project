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
        <div>
            @section('page_title', 'Cairkan Kredit')
        </div>

        <x-button class="d-flex ms-auto">Request Cash out</x-button>

        <div class="row row-cols-1 row-cols-sm-2 g-3">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body p-0">
                        <div class="d-grid gap-3">
                            <span class="text-secondary">Kredit</span>
                            <div>
                                <span class="badge rounded-pill nominal_background">
                                    <h3 class="mb-0 px-2 py-1">Nominal Kredit</h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <div class="card-body p-0">
                        <div class="d-grid gap-3">
                            <h3>Nama Bank Nomor Rekening</h3>
                            <span class="text-secondary lead">a.n. Nama Pemilik Rekening</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="menuTable" class="table-striped table-hover table-borderless table">
                <thead>
                    <tr>
                        <th>Nominal</th>
                        <th>Timestamp</th>
                        <th>Status Pencairan</th>
                    </tr>
                </thead>
                {{-- <tbody></tbody> --}}
                <tbody>
                    <tr>
                        <td>Nominal</td>
                        <td>Timestamp</td>
                        <td>
                            <span
                                class="badge rounded-pill text-success-emphasis bg-success-subtle border-success-subtle border">Status
                                Pencairan</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

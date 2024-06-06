@section('title', 'Menu')

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

    @if (Auth::user()->hasRole('vendor') &&
            ($authDelivery === null || $confirmationDays->confirmation_days === null || $balance === null))
        @include('pages.users.include.settingModal')
    @endif
    @if (Auth::user()->hasRole('customer'))
        <script>
            window.location.href = "/"
        </script>
    @endif

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Kelola Menu')
        </div>

        <x-button class="d-flex ms-auto" onclick="addMenuItem(null)">Tambah Menu</x-button>

        <div class="table-responsive">
            <table id="menuTable" class="table-striped table-hover table-borderless table">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga per Porsi</th>
                        <th>Tipe Pedas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        @include('pages.modals.modal')

        @section('js')
            <script src="{{ asset('js/setting.js') }}"></script>
            <script src="{{ asset('js/menu.js') }}"></script>
        @endsection
    </div>
</x-app-layout>

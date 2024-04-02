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

    @section('page_title', 'Kelola Menu')

    <div class="d-grid gap-3">
    <x-button class="d-flex ms-auto" onclick="addMenuItem()">Add Menu</x-button>

    <div class="table-responsive">
        <table id="menuTable" class="table-striped table-hover table">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Descriptions</th>
                    <th>Image</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    @include('pages.modals.modal')

    @section('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/validate/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/menu.js') }}"></script>
    @endsection
    </div>
</x-app-layout>

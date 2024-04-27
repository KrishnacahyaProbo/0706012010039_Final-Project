@section('title', 'Testimony')

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
            @section('page_title', 'Testimoni')
        </div>

        @for ($i = 0; $i < 3; $i++)
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://laravel.com/img/logotype.min.svg" alt="" width="48">
                            <span>Nama Pelanggan</span>
                        </div>
                        <div class="d-flex gap-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>Rating/5</strong>
                        </div>
                        <span>Isi Testimoni</span>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-secondary">DD/MM/YYYY H:M:S</small>
                </div>
            </div>
        @endfor
    </div>
</x-app-layout>

@section('title', 'Jadwal Penjualan')

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
            @section('page_title', 'Jadwal Penjualan')
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-3">
                    <div class="d-flex ms-auto gap-2">
                        <input type="month" id="monthFilter" class="form-control">
                    </div>
                    <div>
                        <canvas id="scheduleChart" aria-label="Jadwal Penjualan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="calendar" class="vh-100"></div>
            </div>
        </div>

        @section('js')
            <script src="{{ asset('js/schedule.js') }}"></script>
            <script>
                var dataSchedule = <?php echo $data; ?>;
            </script>
        @endsection
    </div>
</x-app-layout>

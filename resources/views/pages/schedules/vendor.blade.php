@section('title', 'Schedules')

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
            <div id="calendar" class="vh-100"></div>
        </div>

        @section('js')
            <script src="{{ asset('js/schedule.js') }}"></script>
            <script>
                var dataSchedule = <?php echo $data; ?>;
                console.log(dataSchedule);
            </script>
        @endsection
    </div>
</x-app-layout>

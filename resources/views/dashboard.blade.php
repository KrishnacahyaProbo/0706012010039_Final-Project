@section('title', 'Dasbor')

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
            @section('page_title', 'Dasbor')
        </div>

        <div class="card">
            <h3>Test</h3>
            <p class="text-secondary">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi nemo repudiandae at
                veritatis repellat ut, magnam voluptatibus? Dolores eaque libero quos nulla laborum omnis. Quidem
                dignissimos aperiam neque commodi ullam.</p>
        </div>
    </div>
</x-app-layout>

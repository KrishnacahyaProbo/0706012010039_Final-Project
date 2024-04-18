@if ($errors->any())
    <div {{ $attributes }}>
        <div class="alert alert-danger pb-0" role="alert">
            <p>Whoops! Something went wrong.</p>

            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if ($errors->any())
    <div {{ $attributes }}>
        <div class="text-danger">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="text-danger mt-3 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

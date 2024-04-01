<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-danger']) }}>
    {{ $slot }}
</button>

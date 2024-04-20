<div class="card">
    <div class="card-body">
        <x-section-title>
            <x-slot name="title">{{ $title }}</x-slot>
            <x-slot name="description">{{ $description }}</x-slot>
        </x-section-title>

        <div>
            {{ $content }}
        </div>
    </div>
</div>

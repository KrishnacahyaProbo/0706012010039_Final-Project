@props(['submit'])

<div class="card">
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div>
        <form wire:submit="{{ $submit }}">
            <div class="{{ isset($actions) }} d-grid gap-3">
                <div>{{ $form }}</div>
            </div>

            @if (isset($actions))
                <div>{{ $actions }}</div>
            @endif
        </form>
    </div>
</div>

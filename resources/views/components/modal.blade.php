@props(['id'])

@php
    $id = $id ?? md5($attributes->wire('model'));
@endphp

<div x-data="{ show: @entangle($attributes->wire('model')) }" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    id="{{ $id }}" class="jetstream-modal fixed" style="display: none;">

    <hr>

    <div x-show="show">
        {{ $slot }}
    </div>
</div>

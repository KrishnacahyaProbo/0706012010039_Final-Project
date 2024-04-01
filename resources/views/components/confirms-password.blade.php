@props([
    'title' => __('Confirm Password'),
    'content' => __('For your security, please confirm your password to continue.'),
    'button' => __('Confirm'),
])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span {{ $attributes->wire('then') }} x-data x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
    {{ $slot }}
</span>

@once
    <x-dialog-modal wire:model.live="confirmingPassword">
        <x-slot name="title">
            {{ $title }}
        </x-slot>

        <x-slot name="content">
            {{ $content }}

            <div x-data="{}"
                x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input type="password" placeholder="{{ __('Password') }}" autocomplete="current-password"
                    x-ref="confirmable_password" wire:model="confirmablePassword" wire:keydown.enter="confirmPassword" />

                <x-input-error for="confirmable_password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="d-grid d-lg-flex gap-2">
                <div>
                    <x-secondary-button class="w=100" wire:click="stopConfirmingPassword" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                </div>

                <div>
                    <x-button class="w=100" dusk="confirm-password-button" wire:click="confirmPassword" wire:loading.attr="disabled">
                        {{ $button }}
                    </x-button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
@endonce

<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Perbarui Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
    </x-slot>

    <x-slot name="form">
        <div class="d-grid gap-3">
            <div>
                <x-label for="current_password" value="{{ __('Password Terkini') }}" />
                <x-input id="current_password" type="password" wire:model="state.current_password"
                    autocomplete="current-password" />
                <x-input-error for="current_password" class="mt-2" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Password Baru') }}" />
                <x-input id="password" type="password" wire:model="state.password" autocomplete="new-password" />
                <x-input-error for="password" class="mt-2" />
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" />
                <x-input id="password_confirmation" type="password" wire:model="state.password_confirmation"
                    autocomplete="new-password" />
                <x-input-error for="password_confirmation" class="mt-2" />
            </div>
    </x-slot>
    </div>

    <x-slot name="actions">
        <x-action-message class="my-3" on="saved">{{ __('Saved.') }}</x-action-message>

        <x-button class="w-100">{{ __('Save') }}</x-button>
    </x-slot>
</x-form-section>

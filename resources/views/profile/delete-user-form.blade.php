<x-action-section>
    <x-slot name="title">
        {{ __('Hapus akun') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Hapus akun Anda secara permanen.') }}
    </x-slot>

    <x-slot name="content">
        <p>{{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>

        <div>
            <x-danger-button class="w-100" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Hapus Akun') }}
            </x-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Hapus Akun') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda? Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}

                <div class="mt-4" x-data="{}"
                    x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-label for="password" value="{{ __('Kata Sandi') }}" />
                    <x-input type="password" autocomplete="current-password" placeholder="{{ __('Kata Sandi') }}"
                        x-ref="password" wire:model="password" wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button class="w-100" wire:click="$toggle('confirmingUserDeletion')"
                    wire:loading.attr="disabled">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="w-100" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>

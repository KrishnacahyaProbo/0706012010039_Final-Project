<x-action-section>
    <x-slot name="title">
        {{ __('Sesi Peramban') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Kelola dan keluar dari sesi aktif Anda di peramban dan perangkat lain.') }}
    </x-slot>

    <x-slot name="content">
        <p>{{ __('Jika perlu, Anda dapat keluar dari semua sesi peramban lainnya di semua perangkat Anda. Beberapa sesi terbaru Anda tercantum di bawah; namun, daftar ini mungkin tidak lengkap. Jika Anda merasa akun Anda telah disusupi, Anda juga harus memperbarui kata sandi Anda.') }}
        </p>

        @if (count($this->sessions) > 0)
            <div>
                <!-- Other Browser Sessions -->
                <ol>
                    @foreach ($this->sessions as $session)
                        <li>
                            {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} -

                            {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }} -

                            {{ $session->ip_address }} -

                            @if ($session->is_current_device)
                                <span
                                    class="badge rounded-pill text-success-emphasis bg-success-subtle border-success-subtle border">{{ __('This device') }}</span>
                            @else
                                {{ __('Last active') }} {{ $session->last_active }}.
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        <div>
            <x-button class="w-100" wire:click="confirmLogout" wire:loading.attr="disabled">
                {{ __('Keluarkan Sesi Peramban Lainnya') }}
            </x-button>

            <x-action-message class="my-3" on="loggedOut">
                {{ __('Done.') }}
            </x-action-message>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="content">
                {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}

                <div x-data="{}"
                    x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input type="password" autocomplete="current-password" placeholder="{{ __('Password') }}"
                        x-ref="password" wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button class="w-100" wire:click="$toggle('confirmingLogout')"
                    wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="w-100" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                    {{ __('Keluarkan Sesi Peramban Lainnya') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>

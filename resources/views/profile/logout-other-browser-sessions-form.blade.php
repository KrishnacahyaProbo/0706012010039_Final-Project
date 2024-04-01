<x-action-section>
    <x-slot name="title">
        {{ __('Browser Sessions') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and log out your active sessions on other browsers and devices.') }}
    </x-slot>

    <x-slot name="content">
        <p>{{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
        </p>

        @if (count($this->sessions) > 0)
            <div>
                <!-- Other Browser Sessions -->
                <ol>
                    @foreach ($this->sessions as $session)
                        <li>
                            {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} -

                            {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}

                            {{ $session->ip_address }} -

                            @if ($session->is_current_device)
                                <span class="fw-bold text-success">{{ __('This device') }}</span>
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
                {{ __('Log Out Other Browser Sessions') }}
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
                    {{ __('Log Out Other Browser Sessions') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>

<x-action-section>
    <x-slot name="title">
        {{ __('Autentikasi Dua Faktor') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Tambahkan keamanan tambahan ke akun Anda menggunakan autentikasi dua faktor.') }}
    </x-slot>

    <x-slot name="content">

        @if ($this->enabled)
            @if ($showingConfirmation)
                <strong>{{ __('Finish enabling two factor authentication.') }}</strong>
            @else
                <strong class="text-success">{{ __('Autentikasi dua faktor telah aktif.') }}</strong>
            @endif
        @else
            <strong class="text-danger">{{ __('Autentikasi dua faktor belum aktif.') }}</strong>
        @endif

        <p>{{ __('Jika autentikasi dua faktor diaktifkan, Anda akan dimintai token acak yang aman selama autentikasi. Anda dapat mengambil token ini dari aplikasi Google Authenticator ponsel Anda.') }}
        </p>

        @if ($this->enabled)
            @if ($showingQrCode)
                <hr>

                <div>
                    <p>
                        @if ($showingConfirmation)
                            {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                        @else
                            {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                        @endif
                    </p>
                </div>

                <div class="d-grid gap-1">
                    <div>
                        {!! $this->user->twoFactorQrCodeSvg() !!}
                    </div>

                    <div>
                        <p>{{ __('Setup Key') }}: {{ decrypt($this->user->two_factor_secret) }}</p>
                    </div>
                </div>

                @if ($showingConfirmation)
                    <div>
                        <x-label for="code" value="{{ __('Code') }}" />
                        <x-input id="code" type="text" name="code" inputmode="numeric" autofocus
                            autocomplete="one-time-code" wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />

                        <x-input-error for="code" class="mt-2" />
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div>
                    <p>
                        {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                    </p>
                </div>

                <div>
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-3">
            @if (!$this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button class="w-100" type="button" wire:loading.attr="disabled">
                        {{ __('Aktifkan') }}
                    </x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-secondary-button>
                            {{ __('Buat Ulang Recovery Codes') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <x-button type="button" wire:loading.attr="disabled">
                            {{ __('Konfirmasi') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-secondary-button>
                            {{ __('Tampilkan Recovery Codes') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-secondary-button wire:loading.attr="disabled">
                            {{ __('Batal') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-danger-button wire:loading.attr="disabled">
                            {{ __('Nonaktifkan') }}
                        </x-danger-button>
                    </x-confirms-password>
                @endif
            @endif
        </div>
    </x-slot>
</x-action-section>

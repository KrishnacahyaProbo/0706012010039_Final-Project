@section('title', 'Profil')

<x-app-layout>
    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Profil')
        </div>

        <div class="d-grid gap-3">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
            @endif
        </div>

        <div class="d-grid gap-3">
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div>
                    @livewire('profile.update-password-form')
                </div>
            @endif
        </div>

        <div>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div>
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif
        </div>

        <div>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <div>
                @livewire('profile.delete-user-form')
            </div>
        @endif
    </div>
</x-app-layout>

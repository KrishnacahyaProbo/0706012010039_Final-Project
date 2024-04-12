<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <div class="d-grid gap-3">
            <!-- Profile Photo -->
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{ photoName: null, photoPreview: null }">
                    <!-- Profile Photo File Input -->
                    <input type="file" id="photo" class="d-none" wire:model.live="photo" x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

                    <x-label for="photo" value="{{ __('Foto Profil') }}" />

                    <!-- Current Profile Photo -->
                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="w-25">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <span class="w-25" x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <x-secondary-button class="mt-2" type="button"
                        x-on:click.prevent="$refs.photo.click()">{{ __('Select A New Photo') }}</x-secondary-button>

                    @if ($this->user->profile_photo_path)
                        <x-danger-button type="button" class="mt-2"
                            wire:click="deleteProfilePhoto">{{ __('Remove Photo') }}</x-danger-button>
                    @endif

                    <x-input-error for="photo" class="mt-2" />
                </div>
            @endif

            <!-- Name -->
            <div>
                <x-label for="name" value="{{ __('Nama') }}" />
                <x-input id="name" type="text" wire:model="state.name" required autocomplete="name" />
                <x-input-error for="name" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" wire:model="state.email" required autocomplete="username" />
                <x-input-error for="email" class="mt-2" />
            </div>
    </x-slot>
    </div>

    <x-slot name="actions">
        <x-action-message class="my-3" on="saved">{{ __('Saved.') }}</x-action-message>

        <x-button class="w-100" wire:loading.attr="disabled" wire:target="photo">{{ __('Save') }}</x-button>
    </x-slot>
</x-form-section>

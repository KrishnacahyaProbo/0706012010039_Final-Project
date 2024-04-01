@section('title', 'Register')

<x-guest-layout>
    @section('page_title', 'Hai, Buat Akun dan Bergabung Sekarang')

    <div class="d-grid gap-2">
        <div>
            <x-authentication-card>
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="d-grid gap-3">
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="mt-1 block w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" class="mt-1 block w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" />
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="mt-1 block w-full" type="password" name="password" required
                                autocomplete="new-password" />
                        </div>

                        <div>
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" class="mt-1 block w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                        </div>

                        <x-button>{{ __('Register') }}</x-button>
                    </div>
                </form>
            </x-authentication-card>
        </div>

        <div>
            <div class="d-flex gap-1">
                <p>Sudah punya akun?</p>
                <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>
</x-guest-layout>

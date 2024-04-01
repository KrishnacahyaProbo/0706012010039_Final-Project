@section('title', 'Forgot Password')

<x-guest-layout>
    @section('page_title', 'Lupa Kata Sandi')

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="text-secondary mb-4">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="text-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="d-grid gap-3">
                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" type="email" name="email" :value="old('email')" required autofocus
                        autocomplete="username" />
                </div>

                <x-button>{{ __('Email Password Reset Link') }}</x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

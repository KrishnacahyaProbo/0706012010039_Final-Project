@section('title', 'Log In')

<x-guest-layout>
    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Selamat Datang Kembali')
        </div>

        <div class="d-grid gap-2">
            <div>
                <x-authentication-card>
                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="text-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="d-grid gap-3">
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" type="email" name="email" :value="old('email')" required
                                    autofocus autocomplete="username" />
                            </div>

                            <div class="d-grid gap-1">
                                <div>
                                    <x-label for="password" value="{{ __('Password') }}" />
                                    <x-input id="password" type="password" name="password" required
                                        autocomplete="current-password" />
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div>
                                        <label for="remember_me" class="d-flex gap-2">
                                            <x-checkbox id="remember_me" name="remember" />
                                            <label class="form-check-label" for="remember_me">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </label>
                                    </div>

                                    <div>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <x-button>{{ __('Log In') }}</x-button>
                        </div>
                    </form>
                </x-authentication-card>
            </div>

            <div>
                <div class="d-flex gap-1">
                    <p>Belum punya akun?</p>
                    <a href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

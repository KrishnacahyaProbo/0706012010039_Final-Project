@section('title', 'Masuk')

<x-guest-layout>
    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Selamat Datang Kembali')
        </div>

        <div class="row align-items-center">
            <div class="col-md-8">
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
                                        <x-label for="email" value="{{ __('Alamat Surel') }}" />
                                        <x-input id="email" type="email" name="email" :value="old('email')" required
                                            autofocus autocomplete="username" />
                                    </div>

                                    <div class="d-grid gap-1">
                                        <div>
                                            <x-label for="password" value="{{ __('Kata Sandi') }}" />
                                            <x-input id="password" type="password" name="password" required
                                                autocomplete="current-password" />
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex gap-2">
                                                <x-checkbox id="remember_me" name="remember" />
                                                <label class="form-check-label" for="remember_me">
                                                    {{ __('Ingat Saya') }}
                                                </label>
                                            </div>

                                            <div>
                                                @if (Route::has('password.request'))
                                                    <a
                                                        href="{{ route('password.request') }}">{{ __('Lupa Kata Sandi?') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <x-button>{{ __('Masuk') }}</x-button>
                                </div>
                            </form>
                        </x-authentication-card>
                    </div>

                    <div>
                        <div class="d-flex gap-1">
                            <span>Belum punya akun?</span>
                            <a href="{{ route('register') }}">Daftar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-none d-md-block">
                <div class="p-2">
                    <img src="{{ url('images/assets/login/login.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

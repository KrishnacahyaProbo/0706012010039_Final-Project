@section('title', 'Daftar')

<x-guest-layout>
    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Halo, Buat Akun dan Bergabung Sekarang')
        </div>

        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-grid gap-2">
                    <div>
                        <x-authentication-card>
                            <x-validation-errors class="mb-4" />

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="d-grid gap-3">
                                    <div>
                                        <x-label for="role" value="{{ __('Peran') }}" />

                                        <div class="d-flex gap-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role"
                                                    id="vendor" value="vendor">
                                                <label class="form-check-label" for="vendor">Vendor</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role"
                                                    id="customer" value="customer">
                                                <label class="form-check-label" for="customer">Pelanggan</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <x-label for="name" value="{{ __('Nama') }}" />
                                        <x-input id="name" type="text" name="name" :value="old('name')" required
                                            autofocus autocomplete="name" />
                                    </div>

                                    <div>
                                        <x-label for="email" value="{{ __('Surel') }}" />
                                        <x-input id="email" type="email" name="email" :value="old('email')" required
                                            autocomplete="username" />
                                    </div>

                                    <div>
                                        <x-label for="password" value="{{ __('Kata Sandi') }}" />
                                        <x-input id="password" type="password" name="password" required
                                            autocomplete="new-password" />
                                    </div>

                                    <div>
                                        <x-label for="password_confirmation" value="{{ __('Konfirmasi Kata Sandi') }}" />
                                        <x-input id="password_confirmation" type="password" name="password_confirmation"
                                            required autocomplete="new-password" />
                                    </div>

                                    <x-button>{{ __('Daftar') }}</x-button>
                                </div>
                            </form>
                        </x-authentication-card>
                    </div>

                    <div>
                        <div class="d-flex gap-1">
                            <span>Sudah punya akun?</span>
                            <a href="{{ route('login') }}">Masuk</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-none d-md-block">
                <div class="p-2">
                    <img src="{{ url('images/assets/register/register.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

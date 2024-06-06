@section('title', 'Lupa Kata Sandi')

<x-guest-layout>
    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Lupa Kata Sandi')
        </div>

        <div class="row align-items-center">
            <div class="col-md-8">
                <x-authentication-card>
                    <div class="text-secondary mb-4">
                        <p>{{ __('Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan email berisi tautan pengaturan ulang kata sandi yang memungkinkan Anda memilih yang baru.') }}
                        </p>
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
                                <x-label for="email" value="{{ __('Surel') }}" />
                                <x-input id="email" type="email" name="email" :value="old('email')" required
                                    autofocus autocomplete="username" />
                            </div>

                            <x-button>{{ __('Kirim') }}</x-button>
                        </div>
                    </form>
                </x-authentication-card>
            </div>

            <div class="col-md-4 d-none d-md-block">
                <div class="p-2">
                    <img src="{{ url('images/assets/forgot_password/forgot_password.svg') }}" alt="">
                </div>
            </div>
        </div>
</x-guest-layout>

@section('title', 'Checkout')

<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Konfirmasi Pembayaran')
        </div>

        <div class="row g-3">
            <div class="col-lg-8 d-grid gap-3">
                {{-- <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="d-grid">
                                    <span class="text-secondary">Alamat Pengiriman</span>
                                    <strong>{{ Auth::user()->address }}</strong>
                                </div>

                                <button class="btn text-primary border-0 p-0">
                                    <a href="{{ route('setting.index') }}">Ubah</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="card">
                    <div class="card-header">
                        <strong>Nama Vendor</strong>
                    </div>
                    <div class="card-body d-grid gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span
                                    class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border">{{ date('l, j F Y') }}</span>
                            </div>
                            <div>
                                <x-secondary-button data-bs-toggle="collapse" data-bs-target="#collapseCatatanPesanan"
                                    aria-expanded="false"
                                    aria-controls="collapseCatatanPesanan">Catatan</x-secondary-button>
                            </div>
                        </div>
                        <div class="collapse" id="collapseCatatanPesanan">
                            <x-label for="catatan" value="{{ __('Catatan Pesanan') }}" />
                            <x-input id="catatan" type="catatan" name="catatan" :value="old('catatan')" />
                        </div>

                        <div>
                            <div class="d-grid d-md-flex justify-content-between align-items-center">
                                <div class="d-grid d-md-flex gap-3">
                                    <img src="https://laravel.com/img/logotype.min.svg" alt=""
                                        class="w-25 rounded-1">
                                    <div class="d-grid gap-2">
                                        <h3>Nama Menu</h3>
                                        <small class="text-secondary">Deskripsi Menu</small>
                                        <h5>Harga/pcs</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="text-secondary">Kredit</span>
                            </div>
                            <div class="col px-1">
                                <span class="badge rounded-pill nominal_background">
                                    <h6 class="mb-0 px-2 py-1">
                                        Rp{{ number_format($balance->credit ?? '0', 0, ',', '.') }}</h6>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <h3>Ringkasan Belanja</h3>
                            <ul class="list-unstyled">
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Total Harga (Total Produk): </span>
                                        <span class="text-break">Nominal</span>
                                    </div>
                                </li>
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Total Ongkos Kirim: </span>
                                        <span class="text-break">Nominal</span>
                                    </div>
                                </li>
                            </ul>

                            <hr class="my-0">

                            <div class="row align-items-center gap-3">
                                <div class="col">
                                    <span class="text-secondary">Total Pembayaran: </span>
                                    <span class="fs-5 text-break"><strong>Nominal</strong></span>
                                </div>
                            </div>

                            <x-button>Pay</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

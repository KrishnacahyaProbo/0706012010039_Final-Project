@section('title', 'Credit')

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
            @section('page_title', 'Keranjang Belanja')
        </div>

        <div class="row g-3">
            <div class="col-lg-8 d-grid gap-3">
                <div>
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between">
                                <div for="select_all" class="d-flex gap-2">
                                    <x-checkbox id="select_all" name="select_all" />
                                    <label class="form-check-label" for="select_all">
                                        <span><strong>Pilih Semua (Total item)</strong></span>
                                    </label>
                                </div>

                                <button class="btn text-danger border-0 p-0">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapseOne">
                                    <div for="selected_vendor" class="d-flex align-items-center gap-2">
                                        <x-checkbox id="selected_vendor" name="selected_vendor" />
                                        <label class="form-check-label" for="selected_vendor">
                                            <strong>Nama Vendor</strong>
                                        </label>
                                    </div>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse show collapse">
                                <div class="accordion-body d-grid gap-3">
                                    <div>
                                        <span
                                            class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border">{{ date('l, j F Y') }}</span>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <x-checkbox id="selected_vendor" name="selected_vendor" />
                                                <div class="d-flex gap-3">
                                                    <img src="https://laravel.com/img/logotype.min.svg" alt="">
                                                    <div class="d-grid gap-2">
                                                        <h3>Nama Menu</h3>
                                                        <small class="text-secondary">Deskripsi Menu</small>
                                                        <h5>Harga/pcs</h5>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span>Porsi</span>
                                                            <div>
                                                                <button
                                                                    class="btn btn-outline-secondary rounded-pill mx-1 px-3">Small</button>
                                                                <button
                                                                    class="btn btn-outline-secondary rounded-pill mx-1 px-3">Medium</button>
                                                                <button
                                                                    class="btn btn-outline-secondary rounded-pill mx-1 px-3">Large</button>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span>Kuantitas</span>
                                                            <div
                                                                class="d-flex align-items-center border-secondary rounded border">
                                                                <button class="btn border-0">
                                                                    <i class="bi bi-dash-lg text-primary"></i>
                                                                </button>
                                                                <span class="mx-2">0</span>
                                                                <button class="btn border-0">
                                                                    <i class="bi bi-plus-lg text-primary"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <button class="btn border-0 p-0" title="Reject Order">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Accordion Item #2
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse show collapse">
                                <div class="accordion-body">
                                    <p>2</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-grid gap-3">
                            <h3>Ringkasan Belanja</h3>
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between">
                                    <span>Nama Vendor</span>
                                    <span>Nominal</span>
                                </li>
                            </ul>

                            <hr class="my-0">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary lead">Total</span>
                                <h5 class="my-0">Nominal</h5>
                            </div>

                            <x-button>Checkout</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

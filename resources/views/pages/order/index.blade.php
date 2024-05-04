@section('title', 'Order')

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

    @if (Auth::user()->hasRole('vendor'))
        <div class="d-grid gap-3">
            <div>
                @section('page_title', 'Kelola Pesanan')
            </div>

            <div class="d-flex ms-auto gap-2">
                <div>
                    <input type="date" name="schedule_date" id="schedule_date" class="form-control">
                </div>
                <div>
                    <select class="form-select" aria-label="Status select" id="vendor_status">
                        <option selected value="customer_paid">Pesanan</option>
                        <option value="vendor_packing">Dikemas</option>
                        <option value="vendor_delivering">Dikirim</option>
                        <option value="customer_received">Diterima</option>
                        <option value="customer_problem">Komplain</option>
                    </select>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="rekapitulasi"></div>

            <div class="table-responsive">
                <table id="orderVendorTable" class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Nama Menu</th>
                            <th>Ukuran Porsi</th>
                            <th>Kuantitas</th>
                            <th>Pelanggan</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    {{-- <tbody>
                        <tr>
                            <td>Pelanggan</td>
                            <td>Nama Menu</td>
                            <td>Ukuran Porsi</td>
                            <td>Harga</td>
                            <td>Alamat</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success" title="Process Order"
                                        onclick="alert('Yakin ingin memproses pesanan?')">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Reject Order"
                                        onclick="alert('Yakin ingin menolak pesanan?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody> --}}
                </table>
            </div>
        </div>

        @include('pages.order.modals.incomingOrderDetail')
    @else
        <div class="d-grid gap-3">
            <div>
                @section('page_title', 'Riwayat Pesanan')
            </div>

            <div class="d-flex ms-auto">
                <select class="form-select" aria-label="Status select" id="customer_status">
                    <option selected value="customer_paid">Lunas</option>
                    <option value="customer_canceled">Dibatalkan</option>
                    <option value="vendor_packing">Dikemas</option>
                    <option value="vendor_delivering">Dikirim</option>
                    <option value="customer_received">Diterima</option>
                    <option value="customer_problem">Komplain</option>
                </select>
            </div>

            <div class="table-responsive">
                <table id="orderCustomerTable" class="table-striped table-hover table-borderless table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Nama Menu</th>
                            <th>Ukuran Porsi</th>
                            <th>Harga Item</th>
                            <th>Kuantitas</th>
                            <th>Total Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        @include('pages.order.modals.requestOrderDetail')

        <div class="modal fade" id="addTestimony" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="addTestimonyTitle">Unggah Testimoni</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="addTestimonyContent">
                        <form method="POST" action="/testimonies/store" id="testimonyForm"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="addTestimonyId" id="addTestimonyId">
                            <input type="hidden" name="vendorId" id="vendorId">
                            <div class="d-grid gap-3">
                                <div class="d-grid">
                                    <x-label for="rating" value="{{ __('Nilai') }}" />
                                    <div class="d-flex gap-2" id="ratingStars">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="bi bi-star text-primary fs-2 star"
                                                data-rating="{{ $i + 1 }}" data-value="1"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="1">
                                </div>
                                <div>
                                    <x-label for="description" value="{{ __('Ulasan') }}" />
                                    <textarea class="form-control" id="description" name="description" required></textarea>
                                </div>
                                <div>
                                    <x-label for="testimony_photo" value="{{ __('Foto') }}" />
                                    <input type="file" class="form-control" id="testimony_photo"
                                        name="testimony_photo">
                                </div>

                                <x-button>{{ __('Save') }}</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        const _APP_URL = {!! '"' . env('APP_URL') . '"' !!}
    </script>
    <script>
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.dataset.rating);
                highlightStars(rating);
                ratingInput.value = rating;
            });
        });
        console.log(ratingInput);

        function highlightStars(selectedRating) {
            stars.forEach((star, index) => {
                const starRating = parseInt(star.dataset.rating);
                if (starRating <= selectedRating) {
                    star.classList.remove('bi-star', 'text-primary');
                    star.classList.add('bi-star-fill', 'text-warning');
                } else {
                    star.classList.remove('bi-star-fill', 'text-warning');
                    star.classList.add('bi-star', 'text-primary');
                }
            });
        }
    </script>
    <script src="{{ asset('/js/order.js') }}"></script>
</x-app-layout>

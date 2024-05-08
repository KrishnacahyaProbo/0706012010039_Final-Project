<div class="modal fade" id="katalog" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="katalogTitle">Katalog {{ $vendor->name }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="katalogContent">
                <div class="alert alert-info" role="alert">
                    <ul class="mb-0">
                        <li>Pemesanan dan Pembatalan paling lambat
                            <strong>H-{{ $vendor->UserSetting->confirmation_days }}</strong>.
                        </li>
                        <li>Melayani pengiriman hingga <strong>{{ $vendor->Delivery->distance_between }} km</strong>.
                        </li>
                    </ul>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @foreach ($vendor->menu as $item)
                        <div class="col">
                            <div class="card h-100">
                                <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('menu/' . $item->image) }}"
                                    alt="" class="card-img-top" loading="lazy">

                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <h5>{{ $item->menu_name }}</h5>
                                        <span>{!! $item->type === 'spicy'
                                            ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pedas</span>'
                                            : '<span class="badge rounded-pill text-primary-emphasis bg-primary-subtle border border-primary-subtle">Tidak Pedas</span>' !!}</span>
                                        <small class="text-secondary">
                                            <pre class="mb-0">{{ $item->description }}</pre>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

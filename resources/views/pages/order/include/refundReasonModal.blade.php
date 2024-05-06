<div class="modal fade" id="addTestimony" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="addTestimonyTitle">Unggah Testimoni</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="addTestimonyContent">
                <form method="POST" action="/testimonies/store" id="testimonyForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="addTestimonyId" id="addTestimonyId">
                    <input type="hidden" name="vendorId" id="vendorId">
                    <div class="d-grid gap-3">
                        <div class="d-grid">
                            <x-label for="rating" value="{{ __('Nilai') }}" />
                            <div class="d-flex gap-2" id="ratingStars">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="bi bi-star text-primary fs-2 star" data-rating="{{ $i + 1 }}"
                                        data-value="1"></i>
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
                            <input type="file" class="form-control" id="testimony_photo" name="testimony_photo">
                        </div>

                        <x-button>{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

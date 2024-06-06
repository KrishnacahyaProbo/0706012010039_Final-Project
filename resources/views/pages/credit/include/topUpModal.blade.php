<div class="modal fade" id="topUpForm" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="topUpFormTitle">Isi Ulang Kredit</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="topUpFormContent">
                <form method="POST" action="/credits/top-up" enctype="multipart/form-data">
                    @csrf

                    <div class="d-grid gap-3">
                        <div>
                            <x-label for="credit" value="{{ __('Nominal') }}" />
                            <x-input id="credit" type="number" name="credit" min="1" required />
                        </div>
                        <div>
                            <x-label for="transaction_proof" value="{{ __('Foto Bukti Top Up') }}" />
                            <input type="file" class="form-control" id="transaction_proof" name="transaction_proof"
                                required>
                        </div>

                        <x-button>{{ __('Kirim') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cashOutForm" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="cashOutFormTitle">Pencairan Kredit</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cashOutFormContent">
                <form method="POST" action="/credits/cash-out">
                    @csrf

                    <div class="d-grid gap-3">
                        <div>
                            <x-label for="credit" value="{{ __('Nominal') }}" />
                            <x-input id="credit_cash_out" type="number" name="credit" min="1" required />
                        </div>

                        <x-button>{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="refundReason" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="refundReasonTitle">Pengajuan Komplain Pesanan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="refundReasonContent">
                <form method="POST" id="refundReasonForm" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <input type="hidden" name="refundReasonId" id="refundReasonId">
                    <input type="hidden" name="vendorId" id="vendorId">
                    <div class="d-grid gap-3">
                        <div>
                            <x-label for="refund_reason" value="{{ __('Keluhan Pesanan') }}" />
                            <select class="form-select" aria-label="Reason select" id="refund_reason"
                                name="refund_reason" onchange="showTextarea(this)">
                                <option value="Kemasan Rusak">Kemasan rusak</option>
                                <option value="Kesalahan item menu">Kesalahan item menu</option>
                                <option value="Kesalahan porsi">Kesalahan porsi</option>
                                <option value="Kesalahan kuantitas">Kesalahan kuantitas</option>
                                <option value="Ketidaksesuaian dengan katalog">Ketidaksesuaian dengan katalog</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div id="otherReason" style="display: none;">
                            <x-label for="other_reason" value="{{ __('Keterangan Lainnya') }}" />
                            <textarea class="form-control" id="other_reason" name="refund_orther_reason"></textarea>
                        </div>
                        <div>
                            <x-label for="reason_proof" value="{{ __('Foto Bukti') }}" />
                            <input type="file" class="form-control" id="reason_proof" name="reason_proof" required>
                        </div>
                        <x-button>{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showTextarea(select) {
        var selectedOption = select.options[select.selectedIndex].value;
        if (selectedOption === 'Lainnya') {
            document.getElementById('otherReason').style.display = 'block';
        } else {
            document.getElementById('otherReason').style.display = 'none';
        }
    }
</script>

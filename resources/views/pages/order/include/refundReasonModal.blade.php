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
                                <option value="kemasan_rusak">Kemasan rusak</option>
                                <option value="kesalahan_item_menu">Kesalahan item menu</option>
                                <option value="kesalahan_porsi">Kesalahan porsi</option>
                                <option value="kesalahan_kuantitas">Kesalahan kuantitas</option>
                                <option value="ketidaksesuaian_dengan_katalog">Ketidaksesuaian dengan katalog</option>
                                <option value="lainnya">Lainnya</option>
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
        if (selectedOption === 'lainnya') {
            document.getElementById('otherReason').style.display = 'block';
        } else {
            document.getElementById('otherReason').style.display = 'none';
        }
    }
</script>

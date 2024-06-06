<div class="modal fade" id="approveRejectComplainModal" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="viewTestimonyTitle">Pengajuan Komplain Pesanan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-3">
                    <h4 id="customer_name_testimony_complain"></h4>
                    <div>
                        <span
                            class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border"
                            id="schedule_date_complain"></span>
                    </div>
                    <p id="name_menu_complain"></p>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <span
                                class="badge rounded-pill text-light-emphasis bg-light-subtle border-light-subtle border"
                                id="portion_complain"></span>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <span id="quantity_complain"></span>
                        </div>
                    </div>
                    <span class="text-secondary">
                        <pre class="mb-0" id="deskripsi_testimony_complain"></pre>
                    </span>
                    <div>
                        <a id="href_complain_image" target="_blank" rel="noopener noreferrer">
                            <img id="image_complain_customer" class="rounded-1" width="196" loading="lazy">
                        </a>
                    </div>

                    <div class="d-grid gap-1">
                        <span>Apakah Anda yakin untuk <strong>menyetujui</strong> pengajuan komplain pesanan
                            tersebut?</span>
                        <div class="d-flex gap-2">
                            <x-checkbox id="include_shipping_costs" name="include_shipping_costs" />
                            <label class="form-check-label" for="include_shipping_costs">
                                {{ __('Termasuk pengembalian ongkos kirim') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="confirm_update_complain_customer">
                    @csrf
                    @method('put')

                    <input type="hidden" name="refund_value" id="refund_value">
                    <button type="submit" class="btn btn-primary" value="approve" name="action">Terima</button>
                    <button type="submit" class="btn btn-outline-primary" value="reject" name="action">Tolak</button>
                </form>
            </div>
        </div>
    </div>
</div>

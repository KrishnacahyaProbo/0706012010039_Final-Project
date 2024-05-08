<div class="modal fade" id="approveRejectComplainModal" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="viewTestimonyTitle">Pengajuan Komplain Pesanan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 id="customer_name_testimony_complain"></h6>
                <div class="d-flex gap-2">
                    <div>
                        <span
                            class="badge rounded-pill text-light-emphasis bg-light-subtle border-light-subtle border"
                            id="name_menu_complain"></span>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <p id="quantity_complain"></p>
                    </div>
                    <p id="schedule_date_complain"></p>
                </div>
                <small class="text-secondary">
                    <pre class="mb-0" id="deskripsi_testimony_complain"></pre>
                </small>
                <div>
                    <a id="href_complain_image" target="_blank" rel="noopener noreferrer">
                        <img  id="image_complain_customer" class="rounded-1" width="196" loading="lazy">
                    </a>
                </div>

            Apakah kamu yakin untuk menyetujui complain? <strong class="text-indigo"
                        id="name_menu_complain"></strong>
                    </div>
            <div class="modal-footer">
                <form action="" method="post" id="confirm_update_complain_customer">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-primary" value="approve" name="action">Approve</button>
                    <button class="btn btn-outline-primary" type="submit"  value="reject" name="action">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailOrder" tabindex="-1" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="detailOrderTitle">Detail Order</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailOrderContent">
                <div class="d-grid gap-3">
                    <span>
                        <span
                            class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border"
                            id="schedule_date"></span>
                    </span>
                    <span id="name"></span>
                    <h4 id="menu_name"></h4>
                    <div class="d-flex gap-2">
                        <div>
                            <span
                                class="badge rounded-pill text-light-emphasis bg-light-subtle border-light-subtle border"
                                id="portion"></span>
                        </div>
                        <div class="vr"></div>
                        <div class="d-flex gap-1">
                            <span id="price"></span>x<span id="quantity"></span>
                        </div>
                    </div>
                    <h5 id="total_price"></h5>
                    <small class="text-secondary">
                        <pre class="mb-0" id="note"></pre>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

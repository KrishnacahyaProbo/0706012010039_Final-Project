document.addEventListener("DOMContentLoaded", function () {
    fetchDataOrderVendorItem();
    fetchDataOrderCustomerItem();
});

// * Vendor
function getCurrentDate() {
    var local = new Date();
    local.setMinutes(local.getMinutes() - local.getTimezoneOffset());
    return local.toJSON().slice(0, 10);
}

$('#schedule_date').val(getCurrentDate());

function fetchDataOrderVendorItem() {
    console.log($('#vendor_status').val());
    $.ajax({
        url: "/orders/incomingOrder",
        method: "GET",
        data: {
            status: $('#vendor_status').val(),
            schedule_date: $('#schedule_date').val(),
        },
        success: function (response) {
            var table = $("#orderVendorTable");
            var rekapitulasi_menu_name = [];

            response.data.forEach(element => {
                var ada = -1;

                // Check if menu_name already exists in rekapitulasi_menu_name
                rekapitulasi_menu_name.forEach(item => {
                    if (element.menu_name === item.menu_name && element.status === 'customer_paid') {
                        item.quantity += element.quantity;
                        ada = 1;
                    }
                });

                // If menu_name doesn't exist in rekapitulasi_menu_name, add it
                if (ada === -1) {
                    rekapitulasi_menu_name.push({
                        menu_name: element.menu_name,
                        quantity: element.quantity,
                    });
                }
            });
            $('#rekapitulasi').html('');

            rekapitulasi_menu_name.forEach(element => {
                var item = '';
                item += `<div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-grid gap-3">
                                        <h3>${element.quantity} pcs</h3>
                                        <span class="text-secondary lead">${element.menu_name}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                $('#rekapitulasi').append(item);
            });

            if ($.fn.DataTable.isDataTable(table)) {
                table.DataTable().destroy();
            }
            table.DataTable({
                processing: true,
                serverSide: true,
                layout: {
                    top: 'buttons',
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                buttons: {
                    buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4],
                        },
                        className: 'btn btn-outline-primary d-flex ms-auto',
                        text: `<strong>Download Report</strong>`,
                    }]
                },
                ajax: {
                    url: "/orders/incomingOrder",
                    type: "GET",
                    data: {
                        status: $('#vendor_status').val(),
                        schedule_date: $('#schedule_date').val(),
                    },
                },
                columns: [
                    {
                        data: "menu_name",
                        name: "menu_name",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "portion",
                        name: "portion",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "quantity",
                        name: "quantity",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data + ' pcs';
                            }
                        },
                    },
                    {
                        data: "name",
                        name: "name",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "address",
                        name: "address",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "action",
                        name: "action",
                        render: function (data, type, row) {
                            console.log(row);
                            let buttons = '';

                            if (row.status === 'customer_paid') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success incoming_order_customer_paid" data-id="${row.detail_id}" title="Process Order"><i class="bi bi-check-circle"></i></button>`;
                            } else if (row.status === 'vendor_packing') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success incoming_order_vendor_packing" data-id="${row.detail_id}" title="Deliver Order"><i class="bi bi-truck"></i></button>`;
                            } else if (row.status === 'customer_received' && row.testimony === 1) {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success incoming_order_vendor_packing" data-id="${row.detail_id}" title="Deliver Order"><i class="bi bi-truck"></i></button>`;
                            } else if (row.status === 'customer_canceled') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                            } else {
                                return '-';
                            }
                            return `<div class="d-flex gap-2">${buttons}</div>`;
                        },
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
        },
    });
}

$(document).on("click", '.incoming_order_customer_paid', function () {
    processOrder($(this).attr('data-id'));
})

$(document).on("click", '.incoming_order_vendor_packing', function () {
    deliverOrder($(this).attr('data-id'));
})

$(document).on("change", '#vendor_status, #schedule_date', function () {
    fetchDataOrderVendorItem();
})

function processOrder(id) {
    var confirmation = window.confirm("Yakin ingin memproses pesanan?");
    if (confirmation) {
        $.ajax({
            url: "/orders/process-order",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                id: id
            },
            success: function (response) {
                toastr.success("Order processed");
                fetchDataOrderVendorItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Error processing order:", error);
            },
        });
    }
}

function deliverOrder(id) {
    var confirmation = window.confirm("Yakin ingin mengirim pesanan?");
    if (confirmation) {
        $.ajax({
            url: "/orders/deliver-order",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                id: id
            },
            success: function (response) {
                toastr.success("Order delivered");
                fetchDataOrderVendorItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Error delivering order:", error);
            },
        });
    }
}

// * Customer
function fetchDataOrderCustomerItem() {
    console.log($('#customer_status').val());
    $.ajax({
        url: "/orders/requestOrder",
        method: "GET",
        data: {
            status: $('#customer_status').val(),
        },
        success: function (response) {
            var table = $("#orderCustomerTable");

            if ($.fn.DataTable.isDataTable(table)) {
                table.DataTable().destroy();
            }
            table.DataTable({
                processing: true,
                serverSide: true,
                layout: {
                    top: 'buttons',
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                buttons: {
                    buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                        },
                        className: 'btn btn-outline-primary d-flex ms-auto',
                        text: `<strong>Download Report</strong>`,
                    }]
                },
                ajax: {
                    url: "/orders/requestOrder",
                    type: "GET",
                    data: {
                        status: $('#customer_status').val(),
                    },
                },
                columns: [
                    {
                        data: "schedule_date",
                        name: "schedule_date",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return moment(data).format('dddd, D MMMM YYYY');
                            }
                        },
                    },
                    {
                        data: "name",
                        name: "name",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "menu_name",
                        name: "menu_name",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "portion",
                        name: "portion",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: "price",
                        name: "price",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return 'Rp' + formatRupiah(data);
                            }
                        },
                    },
                    {
                        data: "quantity",
                        name: "quantity",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return data + ' pcs';
                            }
                        },
                    },
                    {
                        data: "total_price",
                        name: "total_price",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>-</p>';
                            } else {
                                return 'Rp' + formatRupiah(data);
                            }
                        },
                    },
                    {
                        data: "action",
                        name: "action",
                        render: function (data, type, row) {
                            console.log(row);
                            let buttons = '';

                            if (row.status === 'customer_paid') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                if (row.rule === 1) {
                                    buttons += `<button class="btn btn-outline-danger request_order_customer_unpaid" data-id="${row.detail_id}" title="Cancel Order"><i class="bi bi-x-circle"></i></button>`;
                                }
                            } else if (row.status === 'customer_canceled') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                            } else if (row.status === 'vendor_packing') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                            } else if (row.status === 'vendor_delivering') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success request_order_customer_received" data-id="${row.detail_id}" title="Receive Order"><i class="bi bi-check-circle"></i></button>`;
                            } else if (row.status === 'customer_received' && row.testimony === 0) {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success request_order_add_testimony" data-id="${row.detail_id}" vendor-id="${row.vendor_id}" title="Add Testimony"><i class="bi bi-chat-left-text"></i></button>`;
                            } else if (row.status === 'customer_problem') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-danger request_order_customer_problem" data-id="${row.detail_id}" title="Order Problem"><i class="bi bi-exclamation-circle"></i></button>`;
                            } else {
                                return '-';
                            }
                            return `<div class="d-flex gap-2">${buttons}</div>`;
                        },
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
        },
    });
}

$(document).on("click", '.request_order_customer_paid', function () {
    cancelOrder($(this).attr('data-id'));
})

$(document).on("click", '.request_order_customer_received', function () {
    receiveOrder($(this).attr('data-id'));
})

$(document).on("click", '.request_order_add_testimony', function () {
    $('#addTestimonyId').val($(this).attr('data-id'));
    $('#vendorId').val($(this).attr('vendor-id'));
    $('#addTestimony').modal('show');
})

$(document).on("change", '#customer_status', function () {
    fetchDataOrderCustomerItem();
})

function detailOrder(id, schedule_date, name, menu_name, portion, price, quantity, total_price, note) {
    $('#detailOrder').modal('show');

    document.getElementById('schedule_date').innerHTML = moment(schedule_date).format('dddd, D MMMM YYYY');
    document.getElementById('name').innerHTML = name;
    document.getElementById('menu_name').innerHTML = menu_name;
    document.getElementById('portion').innerHTML = portion;
    document.getElementById('price').innerHTML = 'Rp' + formatRupiah(price);
    document.getElementById('quantity').innerHTML = quantity + ' pcs';
    document.getElementById('total_price').innerHTML = 'Rp' + formatRupiah(total_price);
    document.getElementById('note').innerHTML = note;
}

function cancelOrder(id) {
    var confirmation = window.confirm("Yakin ingin membatalkan pesanan?");
    if (confirmation) {
        $.ajax({
            url: "/orders/cancel-order",
            method: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                id: id
            },
            success: function (response) {
                toastr.success("Order canceled");
                fetchDataOrderCustomerItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Error canceling order:", error);
            },
        });
    }
}

function receiveOrder(id) {
    var confirmation = window.confirm("Yakin telah menerima pesanan sesuai dengan kondisi yang diinginkan?");
    if (confirmation) {
        $.ajax({
            url: "/orders/receive-order",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                id: id
            },
            success: function (response) {
                toastr.success("Order received");
                fetchDataOrderCustomerItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Error receiving order:", error);
            },
        });
    }
}

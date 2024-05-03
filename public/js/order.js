document.addEventListener("DOMContentLoaded", function () {
    fetchDataOrderCustomerItem();
});

function fetchDataOrderCustomerItem() {
    console.log($('#customer_status').val());
    $.ajax({
        url: "/orders/data",
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
                    url: "/orders/data",
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

                            if (row.status === 'customer_unpaid') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-outline-danger customer_unpaid" data-id="${row.detail_id}" title="Cancel Order"><i class="bi bi-x-circle"></i></button>`;
                            } else if (row.status === 'customer_paid') {
                                return '-';
                            } else if (row.status === 'customer_canceled') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                            } else if (row.status === 'vendor_packing') {
                                return '-';
                            } else if (row.status === 'vendor_delivering') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success vendor_delivering" data-id="${row.detail_id}" title="Receive Order"><i class="bi bi-check-circle"></i></button>`;
                            } else if (row.status === 'customer_received' && row.testimony === 0) {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-info customer_received" data-id="${row.detail_id}" vendor-id="${row.vendor_id}" title="Add Testimony"><i class="bi bi-chat-left-text"></i></button>`;
                            } else if (row.status === 'customer_problem') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrder('${row.detail_id}', '${row.schedule_date}', '${row.name}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-danger customer_problem" data-id="${row.detail_id}" title="Order Problem"><i class="bi bi-exclamation-circle"></i></button>`;
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

$(document).on("click", '.customer_unpaid', function () {
    cancelOrder($(this).attr('data-id'));
})

$(document).on("click", '.vendor_delivering', function () {
    receiveOrder($(this).attr('data-id'));
})

$(document).on("click", '.customer_received', function () {
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

function addTestimony(id) {
    $.ajax({
        url: "/orders/receive-order",
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id: id
        },
        success: function (response) {
            toastr.success("Testimony added");
            fetchDataOrderCustomerItem();
        },
        error: function (xhr, status, error) {
            toastr.error("Error adding testimony:", error);
        },
    });
}

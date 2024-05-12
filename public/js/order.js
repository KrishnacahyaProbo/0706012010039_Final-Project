document.addEventListener("DOMContentLoaded", function () {
    fetchDataOrderVendorItem();
    fetchDataOrderCustomerItem();
});

// Menambahkan waktu terkini pada judul file saat export (mengunduh laporan)
var currentDate = new Date();
var day = currentDate.getDate();
var month = currentDate.getMonth() + 1;
var year = currentDate.getFullYear();
var hours = currentDate.getHours();
var minutes = currentDate.getMinutes();
var seconds = currentDate.getSeconds();

var exportDateTime = day + "-" + month + "-" + year + " " + hours + "." + minutes + "." + seconds;

// * Vendor
function getCurrentDate() {
    var local = new Date();
    local.setMinutes(local.getMinutes() - local.getTimezoneOffset());
    return local.toJSON().slice(0, 10);
}

$('#schedule_date').val(getCurrentDate());

function fetchDataOrderVendorItem() {
    // Mengolah status transaksi pada judul file saat export (mengunduh laporan)
    const vendorStatusMapping = {
        customer_paid: 'Pesanan',
        vendor_packing: 'Dikemas',
        vendor_delivering: 'Dikirim',
        customer_received: 'Diterima',
        customer_complain: 'Komplain'
    };

    const vendorStatusText = vendorStatusMapping[$('#vendor_status').val()];

    $.ajax({
        url: "/orders/incoming-order",
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
                    if (element.menu_name === item.menu_name) {
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
                        className: 'btn btn-primary d-flex ms-auto',
                        filename: 'Laporan Penjualan ' + vendorStatusText + ' ' + exportDateTime,
                        title: 'Laporan Penjualan ' + vendorStatusText + ' ' + exportDateTime,
                        text: `<strong>Download Report</strong>`,
                    }]
                },
                ajax: {
                    url: "/orders/incoming-order",
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
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderVendor('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success incoming_order_customer_paid" data-id="${row.detail_id}" title="Process Order"><i class="bi bi-check-circle"></i></button>`;
                            } else if (row.status === 'vendor_packing') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderVendor('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success incoming_order_vendor_packing" data-id="${row.detail_id}" title="Deliver Order"><i class="bi bi-truck"></i></button>`;
                            } else if (row.status === 'customer_received') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderVendor('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                                if (row.testimony === 1) {
                                    buttons += `<button class="btn btn-success" title="View Testimony" onclick="testimonyData('${row.name}', '${row?.testimonies?.rating}', '${row?.testimonies?.description}', '${row?.testimonies?.testimony_photo}')"><i class="bi bi-eye"></i></button>`;
                                }
                            } else if (row.status === 'customer_complain') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderVendor('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-warning" data-id="${row.detail_id}" title="Handle Complain" onclick="handleComplain('${row.name}','${row.refund_reason}', '${row.reason_proof}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.detail_id}')"><i class="bi bi-eye"></i></button>`;
                            } else {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderVendor('${row.detail_id}', '${row.menu_name}', '${row.portion}', '${row.quantity}', '${row.name}', '${row.address}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
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

$(document).on("click", '.incoming_order_view_testimony', function () {
    var id = $(this).attr('data-id');
    viewTestimony(id);
})

$(document).on("change", '#include_shipping_costs', function () {
    if ($(this).is(":checked")) {
        $('#refund_value').val(1);
    } else {
        $('#refund_value').val(0);
    }
})

$(document).on("change", '#vendor_status, #schedule_date', function () {
    fetchDataOrderVendorItem();
})

function testimonyData(name, rating, description, photo) {
    $('#detailTestimonyOrderNew').modal('show');

    document.getElementById("customer_name_testimony").innerHTML = name
    document.getElementById('rating_testimony').innerHTML = rating
    document.getElementById('deskripsi_testimony').innerHTML = description
    document.getElementById('image_testimony').src = `/assets/image/testimony_photo/${photo}`
    document.getElementById('href_testimony').href = `/assets/image/testimony_photo/${photo}`
}

function handleComplain(name, refund_reason, reason_proof, schedule_date, menu_name, portion, quantity, transaction_id) {
    $("#approveRejectComplainModal").modal('show');

    document.getElementById('customer_name_testimony_complain').innerHTML = name;
    document.getElementById('deskripsi_testimony_complain').innerHTML = refund_reason;
    document.getElementById('deskripsi_testimony_complain').innerHTML = refund_reason;
    if (reason_proof != 'null') {
        $('#href_complain_image').show();
        document.getElementById('image_complain_customer').src = `/assets/image/reason_proof/${reason_proof}`;
        document.getElementById('href_complain_image').href = `/assets/image/reason_proof/${reason_proof}`;
    } else {
        $('#href_complain_image').hide();
    }
    document.getElementById('name_menu_complain').textContent = menu_name;
    document.getElementById('portion_complain').textContent = portion;
    document.getElementById('quantity_complain').textContent = quantity + " pcs";
    document.getElementById('schedule_date_complain').textContent = moment(schedule_date).format('dddd, D MMMM YYYY');

    $("#confirm_update_complain_customer").attr('action', `/orders/complain-reason/${transaction_id}`)
}

function detailOrderVendor(id, menu_name, portion, quantity, name, address, note, updated_at) {
    $('#detailOrderVendor').modal('show');

    document.getElementById('menu_name').innerHTML = menu_name;
    document.getElementById('portion').innerHTML = portion;
    document.getElementById('quantity').innerHTML = quantity + ' pcs';
    document.getElementById('name').innerHTML = name;
    document.getElementById('address').innerHTML = address;
    document.getElementById('note').innerHTML = note !== 'null' ? note : '';
    document.getElementById('updated_at').innerHTML = updated_at !== null ? moment(updated_at).format('dddd, D MMMM YYYY HH:mm:ss') : '';
}

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
                toastr.success("Berhasil memproses pesanan.");
                fetchDataOrderVendorItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Gagal memproses pesanan. ", error);
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
                toastr.success("Berhasil mengirim pesanan.");
                fetchDataOrderVendorItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Gagal mengirim pesanan. ", error);
            },
        });
    }
}

function viewTestimony(id) {
    $.ajax({
        url: "/orders/view-testimony",
        method: "GET",
        data: {
            id: id,
            status: $('#vendor_status').val(),
            schedule_date: $('#schedule_date').val()
        },
        success: function (response) {
            var content = '';

            response.forEach(function (transactionDetail) {
                // Check if there are testimonies for this transaction detail
                if (transactionDetail.testimonies.length > 0) {
                    content += '<div class="d-grid gap-3">';
                    content += '<h5>' + transactionDetail.menu.menu_name + '</h5>';

                    // Loop through each testimony
                    transactionDetail.testimonies.forEach(function (testimony) {
                        content += '<h6>' + testimony.customer.name + '</h6>';
                        content += '<div class="d-flex gap-2">';
                        content += '<i class="bi bi-star-fill text-warning"></i>';
                        content += '<span>' + testimony.rating + '/5</span>';
                        content += '</div>';
                        content += '<span class="text-secondary"><pre class="mb-0">' + testimony.description + '</pre></span>';
                        content += '<div>';
                        content += '<a href="/assets/image/testimony_photo/' + testimony.testimony_photo + '" target="_blank" rel="noopener noreferrer">';
                        content += '<img src="/assets/image/testimony_photo/' + testimony.testimony_photo + '" alt="" class="rounded-1" width="196" loading="lazy">';
                        content += '</a>';
                        content += '</div>';
                        content += '</div>';
                    });
                }
            });

            $('#viewTestimonyContent').html(content);
            $('#viewTestimonyModal').modal('show');
        },
        error: function (xhr, status, error) {
            toastr.error("Gagal menampilkan testimoni.");
        },
    });
}

function fetchDataOrderCustomerItem() {
    // Mengolah status transaksi pada judul file saat export (mengunduh laporan)
    const customerStatusMapping = {
        customer_paid: "Lunas",
        customer_canceled: "Dibatalkan",
        vendor_packing: "Dikemas",
        vendor_delivering: "Dikirim",
        customer_received: "Diterima",
        customer_complain: "Komplain",
        vendor_approved_complain: 'Dibatalkan'
    };

    const customerStatusText = customerStatusMapping[$('#customer_status').val()];

    $.ajax({
        url: "/orders/request-order",
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
                        className: 'btn btn-primary d-flex ms-auto',
                        filename: 'Laporan Pembelian ' + customerStatusText + ' ' + exportDateTime,
                        title: 'Laporan Pembelian ' + customerStatusText + ' ' + exportDateTime,
                        text: `<strong>Download Report</strong>`,
                    }]
                },
                ajax: {
                    url: "/orders/request-order",
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
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderCustomer('${row.detail_id}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}')"><i class="bi bi-info-circle"></i></button>`;
                                if (row.rule === 1) {
                                    buttons += `<button class="btn btn-outline-danger request_order_customer_paid" data-id="${row.detail_id}" title="Cancel Order"><i class="bi bi-x-circle"></i></button>`;
                                }
                            } else if (row.status === 'customer_canceled' || row.status === 'vendor_packing') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderCustomer('${row.detail_id}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                            } else if (row.status === 'vendor_delivering') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderCustomer('${row.detail_id}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success request_order_customer_received" data-id="${row.detail_id}" title="Receive Order"><i class="bi bi-check-circle"></i></button>`;
                            } else if (row.status === 'customer_received' && row.testimony === 0) {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderCustomer('${row.detail_id}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                                buttons += `<button class="btn btn-success request_order_add_testimony" data-id="${row.detail_id}" vendor-id="${row.vendor_id}" title="Add Testimony"><i class="bi bi-chat-left-text"></i></button>`;
                                if (row.status === 'customer_received' && row.testimony === 0 && row.reason_proof === null && row.refund_reason === null) {
                                    buttons += `<button class="btn btn-danger" data-id="${row.detail_id}" vendor-id="${row.vendor_id}" onclick="addRequestRefund('${row.detail_id}')" title="Request Refund"><i class="bi bi-exclamation-circle"></i></button>`;
                                }
                            } else if (row.status === 'customer_complain') {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderCustomer('${row.detail_id}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
                            } else {
                                buttons += `<button class="btn btn-info" data-id="${row.detail_id}" title="Detail Order" onclick="detailOrderCustomer('${row.detail_id}', '${row.schedule_date}', '${row.menu_name}', '${row.portion}', '${row.price}', '${row.quantity}', '${row.total_price}', '${row.note}', '${row.updated_at}')"><i class="bi bi-info-circle"></i></button>`;
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

function addRequestRefund(transaction_detail_id) {
    $("#refundReason").modal('show')
    $("#refundReasonForm").attr('action', `/orders/refund-reason/${transaction_detail_id}`)
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

function detailOrderCustomer(id, schedule_date, menu_name, portion, price, quantity, total_price, note, updated_at) {
    $('#detailOrderCustomer').modal('show');

    document.getElementById('schedule_date').innerHTML = moment(schedule_date).format('dddd, D MMMM YYYY');
    document.getElementById('menu_name').innerHTML = menu_name;
    document.getElementById('portion').innerHTML = portion;
    document.getElementById('price').innerHTML = 'Rp' + formatRupiah(price);
    document.getElementById('quantity').innerHTML = quantity + ' pcs';
    document.getElementById('total_price').innerHTML = 'Rp' + formatRupiah(total_price);
    document.getElementById('note').innerHTML = note !== 'null' ? note : '';
    document.getElementById('updated_at').innerHTML = updated_at !== null ? moment(updated_at).format('dddd, D MMMM YYYY HH:mm:ss') : '';
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
                toastr.success("Berhasil membatalkan pesanan.");
                fetchDataOrderCustomerItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Gagal membatalkan pesanan. ", error);
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
                toastr.success("Berhasil menerima pesanan.");
                fetchDataOrderCustomerItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Gagal menerima pesanan. ", error);
            },
        });
    }
}

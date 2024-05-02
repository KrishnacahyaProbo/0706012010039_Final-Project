document.addEventListener("DOMContentLoaded", function () {
    fetchDataOrderCustomerItem();
});

function fetchDataOrderCustomerItem() {
    $.ajax({
        url: "/orders/data",
        method: "GET",
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
                        text: 'Download Report',
                    }]
                },
                ajax: {
                    url: "/orders/data",
                    type: "GET",
                },
                columns: [
                    {
                        data: "schedule_date",
                        name: "schedule_date",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>Null</p>';
                            } else {
                                return moment(data).format('ddd, D MMMM YYYY');
                            }
                        },
                    },
                    {
                        data: "name",
                        name: "name",
                        render: function (data, type, row) {
                            if (data === null) {
                                return '<p>Null</p>';
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
                                return '<p>Null</p>';
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
                                return '<p>Null</p>';
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
                                return '<p>Null</p>';
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
                                return '<p>Null</p>';
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
                                return '<p>Null</p>';
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
                            if (row.status === 'customer_unpaid') {
                                return `<button class="btn btn-outline-danger customer_unpaid" data-id="${row.id}" title="Cancel Order"><i class="bi bi-x-circle"></i></button>`;
                            } else if (row.status === 'customer_paid') {
                                return '-';
                            } else if (row.status === 'customer_canceled') {
                                return '-';
                            } else if (row.status === 'vendor_packing') {
                                return '-';
                            } else if (row.status === 'vendor_delivering') {
                                return `<button class="btn btn-success vendor_delivering" data-id="${row.id}" title="Receive Order"><i class="bi bi-check-circle"></i></button>`;
                            } else if (row.status === 'customer_received') {
                                return `<button class="btn btn-success customer_received" data-id="${row.id}" title="Add Testimony"><i class="bi bi-chat-left-text"></i></button>`;
                            } else if (row.status === 'customer_problem') {
                                return `<button class="btn btn-danger customer_problem" data-id="${row.id}" title="Order Problem"><i class="bi bi-exclamation-circle"></i></button>`;
                            } else {
                                return '-';
                            }
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

function cancelOrder(id) {
    var confirmation = window.confirm("Yakin ingin membatalkan pesanan?");
    if (confirmation) {
        $.ajax({
            url: "/orders/cancelOrder",
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

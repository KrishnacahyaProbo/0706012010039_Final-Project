document.addEventListener("DOMContentLoaded", function () {
    fetchDataMenuItem();
});

function addMenuItem(value) {
    $("#mdlForm").modal("show");
    var form = $("#formMenuVendor");

    if (value != null) {
        $("#mdlFormTitle").html("Ubah Menu");
    } else {
        $("#mdlFormTitle").html("Tambah Menu");
    }

    $("#mdlFormContent").html("");

    // Membuat input elemen form asinkron
    var form = $('<form id="formMenuVendor"></form>');
    form.append(`
    <div class="form-group mb-3" style="display:none;">
        <label for="id" class="form-label">id</label>
        <input type="hidden" class="form-control" id="id" name="id" ${value != null ? 'value="' + value.id + '"' : ""}>
    </div>
    `);
    form.append(`
    <div class="form-group mb-3">
        <label for="menu_name" class="form-label">Nama Menu</label>
        <input type="text" class="form-control" id="menuName" name="menu_name" ${value != null ? 'value="' + value.menu_name + '"' : ""}>
    </div>
    `);
    form.append(`
    <div class="form-group mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    `);

    form.append(`
        <div class="form-group mb-4">
            <label class="form-label" for="formMenuVendor">Foto</label>
            <img id="imagePreview" src="" alt="Preview" style="max-width: 100%; display: none;">
            <input type="file" class="form-control" id="image" name="image">
        </div>
    `);

    form.append(`
    <div class="form-check">
        <input class="form-check-input" type="radio" name="spicy" id="spicy" value="spicy">
        <label class="form-check-label" for="spicy">Ya</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="spicy" id="not_spicy" value="no_spicy">
        <label class="form-check-label" for="not_spicy">Tidak</label>
    </div>
    `);
    form.append(`
    <div id="formMultiple" class="form_multiple">
        <div class="row classformMultiple mb-3" id="row_1">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Porsi</label>
                    <input type="text" class="form-control" name="size[]" id="size_1">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Harga</label>
                    <input type="text" class="form-control price-input" name="price[]" id="price_1">
                </div>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-success addButton"><i class="bi bi-plus-lg"></i></button>
            </div>
        </div>
    </div>
    `);
    form.append(`
    <button type="submit" class="btn btn-primary w-100" id="submitBtn">Save</button>
    `);

    // Menambah form container ke form
    const formContainer = document.createElement("div");
    formContainer.id = "formContainer";
    form.append(formContainer);
    $("#mdlFormContent").append(form);

    // Check if value and value.description are not null
    if (value != null && value.description != null) {
        // Set the value of the textarea
        $("#description").val(value.description);
    }

    // Assuming you have a variable "value" containing the value object
    const imagePreview = document.getElementById('imagePreview');

    // Check if value.image is not null
    if (value != null && value.image != null) {
        // If value.image is not null, set the src attribute of the image preview and display it
        imagePreview.src = '/menu/' + value.image;
        imagePreview.style.display = 'block';
    }

    if (value != null) {
        if (value.type === "no_spicy") {
            // Set the not_spicy radio button as checked
            $("#not_spicy").prop("checked", true);
        } else {
            $("#spicy").prop("checked", true);
        }
    }

    if (value !== null && value.menu_detail !== null) {
        // Iterate over the menu_detail array and append fields
        $("#formMultiple").html("");
        value.menu_detail.forEach((detail, index) => {
            const newRow = `
                    <div class="row classformMultiple" id="row_${index + 1
                }">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label">Size:</label>
                                <input type="text" class="form-control" name="size[]" id="size_${index + 2
                }" value="${detail.size}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label">Price:</label>
                                <input type="text" class="form-control price-input" name="price[]" id="price_${index + 2
                }" value="${formatRupiah(detail.price)}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label"></label>
                                ${index === 0
                    ? '<button type="button" class="btn btn-success addButton"><i class="bi bi-plus-lg"></i></button>'
                    : '<button type="button" class="btn btn-danger removeButton"><i class="bi bi-trash3"></i></button>'
                }
                            </div>
                        </div>
                    </div>
                `;
            $("#formMultiple").append(newRow);
        });
    }

    $("#formMultiple")
        .on("click", ".addButton", function () {
            const counter = $(".classformMultiple").length + 1;
            console.log("counter", counter);
            const newInputSet = $("#row_1").clone();
            newInputSet.attr("id", `row_${counter}`);
            newInputSet
                .find('[id^="size_"]')
                .attr("id", `size_${counter}`)
                .val("");
            newInputSet
                .find('[id^="price_"]')
                .attr("id", `price_${counter}`)
                .val("");
            $(".classformMultiple").last().after(newInputSet);
            if (counter !== 1) {
                newInputSet
                    .find(".addButton")
                    .replaceWith(
                        '<button type="button" class="btn border-0 removeButton"><i class="bi bi-trash3 text-danger"></i></button>'
                    );
            }
        })
        .on("keyup", 'input[name^="price[]"]', function () {
            // Ambil current value pada input field
            let priceText = $(this).val();
            // Hapus semua titik pada input value
            priceText = priceText.replace(/\./g, "");
            // Hapus non-numeric characters pada the input value
            let priceValue = parseFloat(priceText.replace(/[^\d.-]/g, ""));
            // Jika parsed value merupakan angka valid, format menjadi IDR dan letakkan pada input field
            if (!isNaN(priceValue)) {
                $(this).val(formatRupiah(priceValue));
            }
        });

    $(document).on("click", ".removeButton", function () {
        $(this).closest(".row").remove();
    });

    $("#formMenuVendor").on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Ambil CSRF token value
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Tambah CSRF token ke form data
        formData.append('_token', csrfToken);

        $.ajax({
            url: '/users/menu/store',
            type: 'POST',
            data: formData,
            success: function (data) {
                $("#mdlForm").modal("hide");
                fetchDataMenuItem();
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

function fetchDataMenuItem() {
    // Make the AJAX request to fetch data
    $.ajax({
        url: "/users/menu/data",
        method: "GET",
        success: function (response) {
            // Close the loading spinner Swal
            var table = $("#menuTable");

            if ($.fn.DataTable.isDataTable(table)) {
                // If DataTable is already initialized, destroy the existing instance
                table.DataTable().destroy();
            }
            // Initialize DataTables with server-side processing
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/users/menu/data",
                    type: "GET",
                },
                columns: [
                    {
                        data: "menu_name",
                        name: "menu_name",
                        render: function (data, type, row) {
                            // Check if menu name is null
                            if (data === null) {
                                // Return the badge HTML
                                return '<span class="badge badge-danger">Null</span>';
                            } else {
                                // Return the menu name
                                return data;
                            }
                        },
                    },
                    {
                        data: "menu_detail",
                        name: "menu_detail",
                        render: function (data, type, row) {
                            if (data.length > 0) {
                                let list = '<ul>';
                                data.forEach(item => {
                                    console.log(item);
                                    list += '<li>' + item.size + ': ' + formatRupiah(item.price) + '</li>';
                                });
                                list += '</ul>';
                                return list;
                            }
                        },
                    },
                    {
                        data: "type",
                        name: "type",
                        render: function (data, type, row) {
                            if (data === null || data.trim() === "") {
                                return '<span class="badge badge-danger">Null</span>';
                            } else if (data.toLowerCase() === "no_spicy") {
                                return '<span class="badge rounded-pill text-bg-primary">Tidak Pedas</span>';
                            } else {
                                return '<span class="badge rounded-pill text-bg-danger">Pedas</span>';
                            }
                        },
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            var buttons =
                                '<div class="d-flex gap-2">' +
                                '<button class="btn btn-success btn-plus" data-id="' +
                                data.id +
                                '" title="Insert Schedule" onclick="addSchedule(' +
                                row.id + ', \'' + row.menu_name +
                                '\')"><i class="bi bi-calendar-plus"></i></button>' +
                                '<button class="btn btn-info btn-detail" data-id="' +
                                data.id +
                                '" title="Detail Menu" onclick="showDetail(' +
                                row.id +
                                ')"><i class="bi bi-info-circle"></i></button>' +
                                '<button class="btn btn-warning btn-edit" data-id="' +
                                data.id +
                                '" title="Edit Menu" onclick="editMenu(' +
                                row.id +
                                ')"><i class="bi bi-pen"></i></button> ' +
                                '<button class="btn btn-danger btn-delete" data-id="' +
                                data.id +
                                '" title="Delete Menu" onclick="destroy(' +
                                row.id +
                                ')"><i class="bi bi-trash3"></i></button>' +
                                '</div>';
                            return buttons;
                        },
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
            // Close the loading spinner Swal
        },
    });
}

function addSchedule(menuId, menuName) {
    $("#mdlForm").modal("show");

    // Set modal title
    $("#mdlFormTitle").html("Tambah Jadwal");

    // Clear previous content
    $("#mdlFormContent").html("");

    // Create form element with CSRF token input
    var form = $('<form id="formSchedule"></form>').addClass('form-group');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    form.append('<input type="hidden" name="_token" value="' + csrfToken + '">');
    $("#mdlFormContent").append(form);

    // Create label for datetime picker
    var label = $('<label class="form-label" for="scheduleDateTimePicker">Jadwal Penjualan</label>').addClass('form-group');
    form.append(label);

    // Create datetime picker element
    var datetimePickerInput = $('<input type="text" class="form-control mb-3" id="scheduleDateTimePicker" name="scheduleDateTimePicker">').addClass('form-group');

    form.append(datetimePickerInput);

    $("#scheduleDateTimePicker").flatpickr({
        weekNumbers: true,
        dateFormat: "d-m-Y",
        mode: "multiple",
        onChange: function (selectedDates, dateStr, instance) {
            console.log("Selected dates: " + dateStr);
        }
    });

    // Initialize jQuery Validation Plugin
    form.validate({
        rules: {
            scheduleDateTimePicker: {
                required: true,
                // Add more validation rules as needed
            }
        },
        messages: {
            scheduleDateTimePicker: {
                required: "Please enter a schedule date and time",
                // Add more custom error messages as needed
            }
        },
        submitHandler: function (form) {
            var inputDates = $("#scheduleDateTimePicker").val(); // Mendapatkan string tanggal dari input dengan id tertentu
            var currentDate = new Date();

            // Memisahkan string tanggal menjadi array
            var datesArray = inputDates.split(", ");

            // Validasi setiap tanggal dalam array
            var isValidDates = datesArray.map(function (inputDate) {
                var parts = inputDate.split("-"); // Pisahkan tanggal, bulan, dan tahun
                var formattedDate = parts[2] + "-" + parts[1] + "-" + parts[0];
                var selectedDate = new Date(formattedDate);

                var isSameMonth = selectedDate.getMonth() === currentDate.getMonth();
                var isBeforeToday = selectedDate < currentDate;

                return isSameMonth && !isBeforeToday; // Kembalikan true jika tanggal valid, false jika tidak
            });

            // Periksa apakah semua tanggal valid
            var allDatesValid = isValidDates.every(function (valid) {
                return valid;
            });

            if (!allDatesValid) {
                alert("Silahkan memilih tanggal yang benar");
            } else {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/users/menu/addSchedule',
                    type: 'POST',
                    data: {
                        menuId: menuId,
                        scheduleDates: datesArray // Gunakan array tanggal yang valid
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        // Handle success response
                        fetchDataMenuItem();
                        $("#mdlForm").modal("hide");
                    },
                    error: function (xhr, status, error) {
                        fetchDataMenuItem();
                    }
                });
            }
        }
    });

    // Create submit button
    var submitButton = $('<button type="submit" class="btn btn-primary w-100">Save</button>').addClass('form-group');
    form.append(submitButton);

    // Click event handler for the submit button
    submitButton.on("click", function (event) {
        event.preventDefault(); // Prevent default form submission
        if (form.valid()) { // Check if the form is valid
            // If valid, submit the form
            form.submit();
        }
    });
}

function editMenu(menuId) {
    $.ajax({
        url: "/users/menu/show",
        method: "GET",
        data: { id: menuId },
        success: function (response) {
            addMenuItem(response.data);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error(xhr.responseText); // Log the error message to the console for debugging
        },
    });
}

function destroy(menuId) {
    // Perform AJAX request to delete the menu
    $.ajax({
        url: "/users/menu/destroy", // Replace with the actual endpoint to delete the menu
        method: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"), // Add CSRF token
            id: menuId
        },
        success: function (response) {
            // Handle success response (e.g., show success message, update UI)
            console.log("Menu deleted successfully");

            // Call the fetchDataMenuItem function
            fetchDataMenuItem();
        },
        error: function (xhr, status, error) {
            // Handle error response (e.g., show error message, log error)
            console.error("Error deleting menu:", error);
        },
    });
}

function showDetail(id) {
    $.ajax({
        url: "/users/menu/show",
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            // Handle successful response
            // Display the data detail using the response
            if (response.status) {
                $("#mdlFormTitle").html(
                    response.data.menu_name
                );
                $("#mdlFormContent").empty().html(`
                    <img src="/menu/${response.data.image}" class="rounded-1 w-25 mb-3">
                    <div>${response.data.type === 'no_spicy' ? '<span class="badge rounded-pill text-bg-primary">Tidak Pedas</span>' : '<span class="badge rounded-pill text-bg-danger">Pedas</span>'}</div>
                    <p class="text-secondary my-3">${response.data.description}</p>
                `);

                // Append the table for menu details
                var menuDetailTable = `
                    <table id="menuDetailTable" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>Porsi</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${response.data.menu_detail
                        .map(
                            (detail) => `
                                <tr>
                                    <td>${detail.size}</td>
                                    <td>${formatRupiah(detail.price)}</td>
                                </tr>
                            `
                        )
                        .join("")}
                        </tbody>
                    </table>
                    <span>Jadwal Menu</span>
                    <div id="calendar"></div>
                `;
                $("#mdlFormContent").append(menuDetailTable);

                $("#mdlForm").modal("show");
                $('#mdlForm').on('shown.bs.modal', function () {
                    // Inisialisasi FullCalendar
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        // Konfigurasi FullCalendar
                        themeSystem: 'bootstrap5',
                        headerToolbar: {
                            left: 'title',
                            right: 'today prev,next'
                        },
                        footerToolbar: {
                            center: 'dayGridMonth,dayGridWeek,dayGridDay,listWeek'
                        },
                        buttonText: {
                            today: 'Today',
                            month: 'Month',
                            week: 'Week',
                            day: 'Day',
                            list: 'List'
                        },
                        titleFormat: {
                            year: 'numeric',
                            month: 'short'
                        },
                        navLinks: true,
                        events: function (fetchInfo, successCallback, failureCallback) {
                            // Menyusun daftar acara
                            var events = [];
                            // Menyesuaikan gaya CSS acara
                            var eventColor = '#842029'; // Warna latar belakang acara

                            // Mengonversi data jadwal menjadi objek acara dan menambahkannya ke dalam daftar acara
                            response.data.menu_schedule.forEach(function (item) {
                                events.push({
                                    id: item.id,
                                    title: item.schedule,
                                    start: item.schedule,
                                    backgroundColor: eventColor
                                });
                            });

                            // Memanggil callback dengan daftar acara
                            successCallback(events);
                        },
                        eventDidMount: function (arg) {
                            arg.el.style.borderColor = '#842029';
                            arg.el.style.color = '#fff';
                        }
                    });
                    calendar.render();
                });
            } else {
            }
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error(xhr.responseText); // Log the error message to the console for debugging
        },
    });
}

function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, ""),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // Tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah;
}
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
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    `);

    form.append(`
        <div class="form-group mb-3">
            <label class="form-label" for="formMenuVendor">Foto</label>
            <img id="imagePreview" src="" alt="Preview" class="rounded-1 w-25 mb-2" style="display: none;">
            <input type="file" class="form-control" id="image" name="image">
        </div>
    `);

    form.append(`
    <div class="form-group mb-3">
        <label class="form-label" for="type">Tipe Menu Pedas</label>

        <div class="d-flex gap-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="spicy" id="spicy"
                    value="spicy">
                <x-label class="form-check-label" for="spicy">Pedas</x-label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="spicy" id="not_spicy"
                    value="no_spicy">
                <x-label class="form-check-label" for="not_spicy">Tidak Pedas</x-label>
            </div>
        </div>
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
        imagePreview.src = '/menus/' + value.image;
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
                <div class="row classformMultiple mb-3" id="row_${index + 1}">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Porsi</label>
                            <input type="text" class="form-control" name="size[]" id="size_${index + 2}" value="${detail.size}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Harga</label>
                            <input type="text" class="form-control price-input" name="price[]" id="price_${index + 2}" value="${formatRupiah(detail.price)}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label class="form-label"></label>
                            ${index === 0 ? '<button type="button" class="btn btn-success addButton"><i class="bi bi-plus-lg"></i></button>' : '<button type="button" class="btn border-0 removeButton"><i class="bi bi-trash3 text-danger"></i></button>'}
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
            url: '/menus/store',
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
        url: "/menus/data",
        method: "GET",
        success: function (response) {
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
                    url: "/menus/data",
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
                                return '<p>Null</p>';
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
                            console.log(data, 'data');
                            if (data.length > 0) {
                                let list = '<ul>';
                                data.forEach(item => {
                                    console.log(item);
                                    list += '<li>' + item.size + ': ' + formatRupiah(item.price) + '</li>';
                                });
                                list += '</ul>';
                                return list;
                            } else {
                                return '<p>No data.</p>';
                            }
                        },
                    },
                    {
                        data: "type",
                        name: "type",
                        render: function (data, type, row) {
                            if (data === null || data.trim() === "") {
                                return '<p>Null</p>';
                            } else if (data.toLowerCase() === "no_spicy") {
                                return '<span class="badge rounded-pill text-primary-emphasis bg-primary-subtle border border-primary-subtle">Tidak Pedas</span>';
                            } else {
                                return '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pedas</span>';
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
            }
        },
        messages: {
            scheduleDateTimePicker: {
                required: "Please enter a schedule date and time",
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
                    url: '/schedules/store',
                    type: 'POST',
                    data: {
                        menuId: menuId,
                        scheduleDates: datesArray // Gunakan array tanggal yang valid
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
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
        url: "/menus/show",
        method: "GET",
        data: { id: menuId },
        success: function (response) {
            addMenuItem(response.data);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        },
    });
}

function destroy(menuId) {
    // Confirm the deletion
    var confirmation = window.confirm("Yakin ingin hapus menu?");
    if (confirmation) {
        // Perform AJAX request to delete the menu
        $.ajax({
            url: "/menus/destroy",
            method: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                id: menuId
            },
            success: function (response) {
                console.log("Menu deleted successfully");

                // Call the fetchDataMenuItem function
                fetchDataMenuItem();
            },
            error: function (xhr, status, error) {
                console.error("Error deleting menu:", error);
            },
        });
    }
}

function showDetail(id) {
    $.ajax({
        url: "/menus/show",
        method: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            // Display the data detail using the response
            if (response.status) {
                $("#mdlFormTitle").html(
                    response.data.menu_name
                );
                $("#mdlFormContent").empty().html(`
                    <img src="/menus/${response.data.image}" class="rounded-1 w-50 mx-auto d-block mb-3" loading="lazy">
                    <div>${response.data.type === 'no_spicy' ? '<span class="badge rounded-pill text-primary-emphasis bg-primary-subtle border border-primary-subtle">Tidak Pedas</span>' : '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pedas</span>'}</div>
                    <p class="text-secondary my-3">${response.data.description}</p>
                `);

                // Append the table for menu details
                var menuDetailTable = `
                    <table id="menuDetailTable" class="table-striped table-hover table table-borderless">
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
                                    <td>Rp${formatRupiah(detail.price)}</td>
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
                        editable: true, // Enable dragging & resizing
                        eventResizableFromStart: true, // Enable resizing from start
                        events: function (fetchInfo, successCallback, failureCallback) {
                            // Menyusun daftar acara
                            var events = [];
                            // Menyesuaikan gaya CSS acara
                            var eventColor = '#842029'; // Warna latar belakang acara

                            // Mengonversi data jadwal menjadi objek acara dan menambahkannya ke dalam daftar acara
                            response.data.menu_schedule.forEach(function (item) {
                                events.push({
                                    id: item.pivot.id,
                                    title: response.data.menu_name,
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

                            var clickHandler = function (event) {
                                event.preventDefault();
                                var confirmation = window.confirm('Yakin ingin hapus jadwal penjualan?');
                                if (confirmation) {
                                    var eventId = arg.event.id; // Get the event ID
                                    console.log(eventId); // Log the event ID
                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                                    $.ajax({
                                        url: 'schedules/destroy',
                                        type: 'DELETE',
                                        data: {
                                            id: eventId,
                                            _token: csrfToken
                                        },
                                        success: function (response) {
                                            if (response.success) {
                                                // Hapus event dari FullCalendar
                                                var eventToRemove = calendar.getEventById(eventId);
                                                console.log(eventToRemove);
                                                if (eventToRemove) {
                                                    eventToRemove.remove();
                                                }

                                                $("#mdlForm").modal("hide");
                                                $("#mdlFormContent").html("");
                                                setTimeout(function () {
                                                    showDetail(id);
                                                }, 1000);
                                            } else {
                                                alert('Event date update failed. Please try again.');
                                            }
                                        },
                                        error: function (xhr, status, error) {
                                            console.error('Error deleting event:', error);
                                        }
                                    });
                                }
                            };

                            arg.el.addEventListener('click', clickHandler);

                            // Hapus event listener jika event dihapus dari kalender
                            arg.event.remove = function () {
                                arg.el.removeEventListener('click', clickHandler);
                            };
                        },

                        // Menangani event ketika event di-drop (dragged)
                        eventDrop: function (arg) {
                            var eventId = arg.event.id; // ID dari event yang di-drop
                            var newStart = arg.event.start; // Tanggal baru setelah di-drop
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');

                            // Create a new Date object
                            var date = new Date(newStart);

                            // Extract year, month, and day components
                            var year = date.getFullYear();
                            // JavaScript months are 0-based, so we add 1 to get the correct month
                            var month = (date.getMonth() + 1).toString().padStart(2, '0');
                            var day = date.getDate().toString().padStart(2, '0');

                            // Form the yyyy-mm-dd format
                            var formattedDate = year + '-' + month + '-' + day;
                            // Perform AJAX request untuk update tanggal event
                            $.ajax({
                                url: 'schedules/update',
                                type: 'POST',
                                data: {
                                    id: eventId,
                                    new_start: formattedDate,
                                    _token: csrfToken
                                },
                                success: function (response) {
                                    if (response.success) {
                                        $("#mdlForm").modal("hide");
                                        $("#mdlFormContent").html("");
                                        setTimeout(function () {
                                            showDetail(id);
                                        }, 1000);
                                    } else {
                                        alert('Event date update failed. Please try again.');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    alert('Event date update failed. Please try again.', xhr.responseJSON.error);
                                }
                            });
                        },

                        // Menangani event ketika event di-resize
                        eventResize: function (arg) {
                            var eventId = arg.event.id; // ID dari event yang di-resize
                            var newEnd = arg.event.end; // Tanggal baru setelah di-resize
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');

                            // Perform AJAX request untuk update tanggal event
                            $.ajax({
                                url: 'schedules/update',
                                type: 'PUT',
                                data: {
                                    id: eventId,
                                    new_end: newEnd.format(), // Format tanggal baru sesuai kebutuhan Anda
                                    _token: csrfToken
                                },
                                success: function (response) {
                                    console.log('Event date updated successfully');
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error updating event date:', error);
                                }
                            });
                        },
                    });
                    calendar.render();
                });
            } else {
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
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

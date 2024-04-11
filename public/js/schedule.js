document.addEventListener('DOMContentLoaded', function() {
    fetchSchedule();
});

function fetchSchedule(){
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
        dataSchedule.menu.forEach(function(menuItem) {
            menuItem.menu_schedule.forEach(function (scheduleItem) {
                events.push({
                    id: scheduleItem.pivot.id,
                    title: menuItem.menu_name,
                    start: scheduleItem.schedule,
                    backgroundColor: eventColor
                });
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
}

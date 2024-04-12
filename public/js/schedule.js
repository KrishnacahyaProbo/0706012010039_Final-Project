document.addEventListener('DOMContentLoaded', function () {
    fetchSchedule();
});

function fetchSchedule() {
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
            dataSchedule.menu.forEach(function (menuItem) {
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
        },
    });
    calendar.render();
}

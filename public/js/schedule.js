document.addEventListener('DOMContentLoaded', function () {
    fetchSchedule();
});

function fetchSchedule() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
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
            var eventColor = '#842029';

            // Mengonversi data jadwal menjadi objek acara dan menambahkannya ke dalam daftar acara
            dataSchedule.menu.forEach(function (menuItem) {
                menuItem.menu_schedule.forEach(function (scheduleItem) {
                    events.push({
                        id: scheduleItem.pivot.id,
                        title: menuItem.menu_name,
                        start: scheduleItem.schedule,
                        backgroundColor: eventColor,
                        borderColor: eventColor,
                    });
                });
            });

            // Memanggil callback dengan daftar acara
            successCallback(events);
        },
    });
    calendar.render();
}

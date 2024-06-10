document.addEventListener('DOMContentLoaded', function () {
    fetchSchedule();

    function fetchSchedule() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            themeSystem: 'bootstrap5',
            headerToolbar: {
                left: 'title',
                right: 'today prev,next'
            },
            footerToolbar: {
                center: 'dayGridMonth,dayGridWeek,dayGridDay,listWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari',
                list: 'Daftar'
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

    function getCurrentMonth() {
        var local = new Date();
        local.setMinutes(local.getMinutes() - local.getTimezoneOffset());
        return local.toJSON().slice(0, 7); // Return YYYY-MM format
    }

    $('#monthFilter').val(getCurrentMonth());

    // Menambahkan event listener untuk perubahan nilai pada input filter bulan
    document.getElementById('monthFilter').addEventListener('change', function () {
        drawChart();
    });

    function drawChart() {
        // Mendapatkan nilai bulan yang dipilih dari input filter bulan
        var selectedMonth = document.getElementById('monthFilter').value;

        // Menghitung rincian events per hari
        var eventsPerMonth = {};
        dataSchedule.menu.forEach(function (menuItem) {
            menuItem.menu_schedule.forEach(function (scheduleItem) {
                var monthYear = scheduleItem.schedule.slice(0, 7); // Ambil bulan dan tahun (format: yyyy-mm)
                if (monthYear === selectedMonth) {
                    var day = scheduleItem.schedule.slice(0, 10); // Ambil tanggal (format: yyyy-mm-dd)
                    if (eventsPerMonth.hasOwnProperty(day)) {
                        eventsPerMonth[day].push({
                            id: scheduleItem.pivot.id,
                            title: menuItem.menu_name,
                            start: scheduleItem.schedule
                        });
                    } else {
                        eventsPerMonth[day] = [{
                            id: scheduleItem.pivot.id,
                            title: menuItem.menu_name,
                            start: scheduleItem.schedule
                        }];
                    }
                }
            });
        });

        // Menghapus chart sebelumnya jika ada
        var existingChart = Chart.getChart('scheduleChart');
        if (existingChart) {
            existingChart.destroy();
        }

        // Membuat chart baru dengan plugin emptyDoughnut
        var ctx = document.getElementById('scheduleChart').getContext('2d');

        const noData = {
            id: 'noData',
            afterDatasetsDraw: ((chart, args, plugins) => {
                const { ctx, data, chartArea: { left, top, right, bottom, width, height } } = chart;
                ctx.save();

                if (data.datasets.length === 0 || data.datasets[0].data.length === 0) {
                    ctx.fillStyle = 'white';
                    ctx.fillRect(left, top, width, height);

                    ctx.font = 'bold 20px sans-serif';
                    ctx.fillStyle = 'black';
                    ctx.textAlign = 'center';
                    ctx.fillText('Belum terdapat jadwal penjualan.', left + width / 2, top + height / 2);
                }
            })
        };

        var scheduleChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                // Mengurutkan array label secara ascending
                labels: Object.keys(eventsPerMonth).length > 0 ? Object.keys(eventsPerMonth)
                    .sort((a, b) => new Date(a) - new Date(b))
                    .map(function (day) {
                        return new Date(day).toLocaleString('id', { month: 'long', day: 'numeric', year: 'numeric' });
                    }) : ['-'],
                datasets: [{
                    label: 'Jadwal Penjualan' + (Object.keys(eventsPerMonth).length > 0 ? '' : ' - -'),
                    data: Object.values(eventsPerMonth).map(function (events) {
                        return events.length;
                    }),
                    backgroundColor: ['#f8d7da', '#f1aeb5', '#ea868f', '#e35d6a', '#dc3545', '#b02a37', '#842029', '#58151c', '#2c0b0e', '#000000'],
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Jadwal Penjualan Per Hari' + (Object.keys(eventsPerMonth).length > 0 ? ' (' + new Date(selectedMonth).toLocaleString('id', { month: 'long', year: 'numeric' }) + ')' : '')
                    },
                    noData: noData
                }
            },
            plugins: [noData],
        });
    }

    drawChart();
});

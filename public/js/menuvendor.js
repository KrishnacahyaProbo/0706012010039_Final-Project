// Wrap your code inside a function
function initialize() {
    console.log("Initializing...");

    // Your getLocation function
    function getLocation() {
        console.log("vendor", vendorData);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showDistance);
        } else {
            document.getElementById("distance-info").innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    // Your showDistance function
    function showDistance(position) {
        var userLatitude = position.coords.latitude;
        var userLongitude = position.coords.longitude;
        var distance = calculateDistance(userLatitude, userLongitude, vendorData.latitude, vendorData.longitude);
        console.log(distance, 'distance');
        document.getElementById("distance-info").innerHTML = vendorData.address + " - " + distance + "km";
    }

    // Your calculateDistance and toRadians functions
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var earthRadius = 6371; // Radius of the Earth in kilometers
        var dLat = toRadians(lat2 - lat1);
        var dLon = toRadians(lon2 - lon1);
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var distance = earthRadius * c; // Distance in kilometers
        return distance.toFixed(2);
    }

    function toRadians(degrees) {
        return degrees * Math.PI / 180;
    }

    // Call getLocation function when the document is ready
    getLocation();

    var calendarEl = document.getElementById('calendar_menu');
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
        dateClick: function (info) {
            // Extract the clicked date
            var clickedDate = info.date;

            // Format the date (if needed)
            var formattedDate = formatDate(clickedDate); // Implement formatDate function if needed

            // Show the selected date

            $.ajax({
                url: '/menu/scheduleMenu', // Adjust the URL to match your server route
                type: 'GET',
                data: {
                    'date': formattedDate
                },
                success: function (response) {
                    console.log(response.menus);
                    // Create the elements
                    var divCard = document.createElement('div');
                    divCard.classList.add('card');

                    var divCardBody = document.createElement('div');
                    divCardBody.classList.add('card-body', 'd-grid', 'gap-3', 'p-0');

                    var img = document.createElement('img');
                    img.src = 'https://laravel.com/img/logotype.min.svg';
                    img.alt = '';
                    img.classList.add('card-img-top', 'rounded-0');
                    img.setAttribute('loading', 'lazy');

                    var h3 = document.createElement('h3');
                    h3.classList.add('card-title');
                    h3.textContent = 'Nama Menu';

                    var small = document.createElement('small');
                    small.classList.add('card-text', 'text-secondary');
                    small.textContent = '(Deskripsi Menu) Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod odio sit, corporis id nihil eius similique soluta ut ipsum vel impedit rerum possimus modi iusto cumque sint dolores inventore! Delectus.';

                    var h5 = document.createElement('h5');
                    h5.textContent = 'Rp(Harga)/pcs';

                    var divPorsiContainer = document.createElement('div');
                    divPorsiContainer.classList.add('d-flex', 'align-items-center', 'gap-3');
                    var spanPorsi = document.createElement('span');
                    spanPorsi.textContent = 'Porsi';
                    var divPorsiButtons = document.createElement('div');
                    divPorsiButtons.classList.add('d-flex');
                    var porsiOptions = ['Small', 'Medium', 'Large']; // Example porsi options
                    porsiOptions.forEach(function (option) {
                        var button = document.createElement('button');
                        button.classList.add('btn', 'btn-outline-secondary', 'rounded-pill', 'mx-1', 'px-3');
                        button.type = 'button';
                        button.textContent = option;
                        divPorsiButtons.appendChild(button);
                    });
                    divPorsiContainer.appendChild(spanPorsi);
                    divPorsiContainer.appendChild(divPorsiButtons);

                    var divKuantitasContainer = document.createElement('div');
                    divKuantitasContainer.classList.add('d-flex', 'align-items-center', 'gap-3');
                    var spanKuantitas = document.createElement('span');
                    spanKuantitas.textContent = 'Kuantitas';
                    var divKuantitasControls = document.createElement('div');
                    divKuantitasControls.classList.add('d-flex', 'align-items-center', 'border-secondary', 'rounded', 'border');
                    var buttonMinus = document.createElement('button');
                    buttonMinus.classList.add('btn', 'border-0');
                    var iMinus = document.createElement('i');
                    iMinus.classList.add('bi', 'bi-dash-lg', 'text-primary');
                    buttonMinus.appendChild(iMinus);
                    var spanQuantity = document.createElement('span');
                    spanQuantity.classList.add('mx-2');
                    spanQuantity.textContent = '0';
                    var buttonPlus = document.createElement('button');
                    buttonPlus.classList.add('btn', 'border-0');
                    var iPlus = document.createElement('i');
                    iPlus.classList.add('bi', 'bi-plus-lg', 'text-primary');
                    buttonPlus.appendChild(iPlus);
                    divKuantitasControls.appendChild(buttonMinus);
                    divKuantitasControls.appendChild(spanQuantity);
                    divKuantitasControls.appendChild(buttonPlus);
                    divKuantitasContainer.appendChild(spanKuantitas);
                    divKuantitasContainer.appendChild(divKuantitasControls);

                    var addToCartButton = document.createElement('button');
                    addToCartButton.classList.add('w-100', 'btn', 'btn-primary');
                    addToCartButton.textContent = 'Add to Cart';

                    // Append elements to each other
                    divCardBody.appendChild(img);
                    divCardBody.appendChild(h3);
                    divCardBody.appendChild(small);
                    divCardBody.appendChild(h5);
                    divCardBody.appendChild(divPorsiContainer);
                    divCardBody.appendChild(divKuantitasContainer);
                    divCardBody.appendChild(addToCartButton);

                    divCard.appendChild(divCardBody);

                    // Append the card to a container in the DOM
                    var container = document.getElementById('menuCart'); // Change 'your-container-id' to the actual ID of your container
                    container.appendChild(divCard);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching menu schedules:', error);

                    // Call failureCallback with an empty array
                    failureCallback([]);
                }
            });
        },
        events: function (fetchInfo, successCallback, failureCallback) {

            // Memanggil callback dengan daftar acara
            // successCallback(events);
        },
        eventDidMount: function (arg) {
            arg.el.style.borderColor = '#842029';
            arg.el.style.color = '#fff';



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
                url: 'menu/updateSchedule',
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
                url: 'menu/updateSchedule',
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
function formatDate(date) {
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = date.getDate().toString().padStart(2, '0');
    return year + '-' + month + '-' + day;
}
// Call initialize function when the document is loaded
document.addEventListener("DOMContentLoaded", initialize);

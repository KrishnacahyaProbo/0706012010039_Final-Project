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
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: 'title',
            right: 'today prev,next'
        },
        titleFormat: {
            year: 'numeric',
            month: 'short'
        },
        // navLinks: true,
        dateClick: function (info) {
            var clickedDate = info.date;
            var formattedDate = formatDate(clickedDate);

            $.ajax({
                url: '/vendors/' + '{{ $vendor->id }}',
                type: 'GET',
                data: {
                    'vendor_id': '{{ $vendor->id }}',
                    'date': formattedDate
                },
                success: function (response) {
                    console.log(response.menus);
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
                    var container = document.getElementById('menuCart');
                    container.appendChild(divCard);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching menu schedules:', error);

                    // Call failureCallback with an empty array
                    failureCallback([]);
                }
            });
        },
        eventClick: function (info) {
            // Handle event click
            console.log('Event clicked:', info.event);
        }
        // events: function (fetchInfo, successCallback, failureCallback) {

            // Memanggil callback dengan daftar acara
            // successCallback(events);
        // },
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

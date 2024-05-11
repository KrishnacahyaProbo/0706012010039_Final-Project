var currentPage = 1;
var perPage = 12;

document.addEventListener("DOMContentLoaded", function () {
    setVendorToMenu();
});

function searchVendor() {
    setVendorToMenu();
}

function setVendorToMenu() {
    var vendorContainer = $('#vendorContainer');
    vendorContainer.empty();

    $.ajax({
        url: '/vendors/data',
        type: 'GET',
        data: {
            'page': currentPage,
            'perPage': perPage,
            'search': $("#searchInput").val(),
            'latitude': customerLatitude,
            'longitude': customerLongitude
        },
        dataType: 'json',
        success: function (response) {
            if ($("#searchInput").val() != '') {
                $.each(response.data, function (index, vendor) {
                    var cardCol = $('<div class="col d-flex"></div>');
                    var card = $('<div class="card w-100 h-100"></div>');
                    var img = $('<img src="' + (vendor.profile_photo_url != null ? vendor.profile_photo_url : "") + '" alt="" class="card-img-top" loading="lazy">');
                    var cardBody = $('<div class="card-body"></div>');
                    var title = $('<h3 class="card-title"><a href="#" class="stretched-link">' + vendor.name + '</a></h3>');
                    var ratingText = (vendor.rating !== undefined) ? vendor.rating + '/5,0' : '0/5,0';
                    var rating = $('<div class="d-grid gap-1"><div class="d-flex gap-2"><i class="bi bi-star"></i><span class="card-text">' + ratingText + '</span></div></div>');
                    var address = $('<div class="d-flex gap-2"><i class="bi bi-geo-alt"></i><p class="card-text">' + (vendor.vendorAddress) + ' (' + calculateDistance(customerLatitude, customerLongitude, vendor.latitude, vendor.longitude) + ' km)</p></div>');
                    var shipping = $('<div class="d-flex gap-2"><i class="bi bi-truck"></i><p class="card-text">Rp' + (vendor.delivery !== null ? formatRupiah(vendor.delivery.shipping_cost) : '-') + '</p></div>');

                    cardBody.append(title).append(rating).append(address).append(shipping);
                    card.append(img).append(cardBody);
                    cardCol.append(card);
                    vendorContainer.append(cardCol);

                    cardCol.click(function () {
                        // Get vendor_id and vendor_name from data attribute
                        var vendorName = $(this).find('.card-title a').text();
                        var encodedVendorName = encodeURIComponent(vendorName);
                        window.location.href = '/vendors/' + encodedVendorName;
                    });

                    title.click(function () {
                        // Get vendor_id from parent cardCol's data attribute
                        var vendorId = $(this).closest('.col').data('vendor_id');
                        console.log('Clicked on title for vendor_id: ' + vendorId);
                    });
                });

                if (response.data.length == 0) {
                    var alertInfo = $('<div class="w-100"><div class="alert alert-secondary d-grid gap-3 text-center" role="alert"><i class="bi bi-search display-1"></i><span>Vendor tidak ditemukan. Silakan coba kata kunci lain.</span></div></div>');
                    vendorContainer.append(alertInfo);
                }
            } else {
                $.each(response.data.data, function (index, vendor) {
                    console.log(vendor);
                    var cardCol = $('<div class="col d-flex"></div>');
                    var card = $('<div class="card w-100 h-100"></div>');
                    var img = $('<img src="' + (vendor.profile_photo_url != null ? vendor.profile_photo_url : "") + '" alt="" class="card-img-top" loading="lazy">');
                    var cardBody = $('<div class="card-body"></div>');
                    var title = $('<h3 class="card-title"><a href="#" class="stretched-link">' + vendor.name + '</a></h3>');
                    var ratingText = (vendor.rating !== undefined) ? vendor.rating + '/5,0' : '0/5,0';
                    var rating = $('<div class="d-grid gap-1"><div class="d-flex gap-2"><i class="bi bi-star"></i><span class="card-text">' + ratingText + '</span></div></div>');
                    var address = $('<div class="d-flex gap-2"><i class="bi bi-geo-alt"></i><p class="card-text">' + (vendor.vendorAddress) + ' (' + calculateDistance(customerLatitude, customerLongitude, vendor.latitude, vendor.longitude) + ' km)</p></div>');
                    var shipping = $('<div class="d-flex gap-2"><i class="bi bi-truck"></i><p class="card-text">Rp' + (vendor.delivery !== null ? formatRupiah(vendor.delivery.shipping_cost) : '0') + '</p></div>');
                    cardCol.data('vendor_id', vendor.id);

                    cardBody.append(title).append(rating).append(address).append(shipping);
                    card.append(img).append(cardBody);
                    cardCol.append(card);
                    vendorContainer.append(cardCol);

                    cardCol.click(function () {
                        // Get vendor_id and vendor_name from data attribute
                        var vendorName = $(this).find('.card-title a').text();
                        var encodedVendorName = encodeURIComponent(vendorName);
                        window.location.href = '/vendors/' + encodedVendorName;
                    });

                    // Attach click event to title
                    title.click(function () {
                        // Get vendor_id from parent cardCol's data attribute
                        var vendorId = $(this).closest('.col').data('vendor_id');
                        console.log('Clicked on title for vendor_id: ' + vendorId);
                    });
                });

                // Add pagination links
                if (response.data.next_page_url || response.data.prev_page_url) {
                    var paginationHtml = '<ul class="pagination justify-content-center w-100">';

                    if (response.data.prev_page_url) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="previousPage()"><span aria-hidden="true">&laquo;</span></a></li>';
                    }

                    var totalPages = response.data.last_page || response.last_page;
                    for (var i = 1; i <= totalPages; i++) {
                        if (i === currentPage) {
                            paginationHtml += '<li class="page-item active"><a class="page-link" href="#">' + i + '</a></li>';
                        } else {
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="goToPage(' + i + ')">' + i + '</a></li>';
                        }
                    }

                    if (response.data.next_page_url) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="nextPage()"><span aria-hidden="true">&raquo;</span></a></li>';
                    }

                    paginationHtml += '</ul>';

                    vendorContainer.append(paginationHtml);
                }
            }
        },

        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function goToPage(pageNumber) {
    currentPage = pageNumber;
    setVendorToMenu();
}

function nextPage() {
    currentPage++;
    setVendorToMenu();
}

function previousPage() {
    currentPage--;
    setVendorToMenu();
}

function chooseCustomerLocation() {
    if (navigator.geolocation) {
        document.getElementById("customer_address").innerHTML =
            `<span class="placeholder-glow">
                <span class="placeholder col-12"></span>
            </span>`;

        navigator.geolocation.getCurrentPosition(
            function (position) {
                customerLatitude = position.coords.latitude;
                customerLongitude = position.coords.longitude;
                console.log(customerLatitude, customerLongitude);
                convertCustomerAddress(customerLatitude, customerLongitude);
                setVendorToMenu();
            },
            function (error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.error("Tidak dapat mendeteksi lokasi, mohon memberikan izin akses lokasi untuk mencari vendor di area sekitar Anda.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.error("Maaf, informasi lokasi tidak tersedia.");
                        break;
                    case error.TIMEOUT:
                        console.error("Permintaan untuk mendapatkan lokasi pengguna telah habis masa berlakunya.");
                        break;
                    case error.UNKNOWN_ERROR:
                        console.error("Maaf, terjadi kesalahan. Mohon mencoba beberapa saat lagi.");
                        break;
                }
            }
        );
    } else {
        navigator.permissions.query({ name: 'geolocation' }).then(function (result) {
            if (result.state == 'granted') {
                chooseCustomerLocation();
            } else if (result.state == 'denied') {
                alert("Tidak dapat mendeteksi lokasi, mohon memberikan izin akses lokasi untuk mencari vendor di sekitar Anda.");
            }
        });
    }
}

function convertCustomerAddress(latitude, longitude) {
    fetch("https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + latitude + "&lon=" + longitude)
        .then(response => response.json())
        .then(data => {
            var address = "";

            if (data.address) {
                console.log(data.address, "address");
                if (data.address.road) {
                    address += data.address.road + ", ";
                }
                if (data.address.state) {
                    address += data.address.state + ", ";
                }
                if (data.address.municipality) {
                    address += data.address.municipality + ", ";
                }
                if (data.address.city) {
                    address += data.address.city + ", ";
                }
                if (data.address.village) {
                    address += data.address.village + ", ";
                }
                if (data.address.postcode) {
                    address += data.address.postcode + ".";
                }
            }

            document.getElementById("customer_address").innerHTML = address;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function calculateDistance(vendorLatitude, vendorLongitude, customerLatitude, customerLongitude) {
    // Jari-jari (radius) bumi dalam kilometer
    var earthRadiusKilometer = 6371;
    // Konversi latitude pada vendor dari derajat ke radian
    var vendorLatitudeRadians = toRadians(vendorLatitude);
    // Konversi latitude pada customer dari derajat ke radian
    var customerLatitudeRadians = toRadians(customerLatitude);
    // Selisih jarak antara latitude vendor terhadap customer (dalam radian)
    var latitudeDifference = toRadians(vendorLatitude - customerLatitude);
    // Selisih jarak antara longitude vendor terhadap customer (dalam radian)
    var longitudeDifference = toRadians(vendorLongitude - customerLongitude);

    // Perhitungan jarak sudut antara titik lokasi vendor terhadap customer pada permukaan bola (seperti Bumi)
    var angularDistance = Math.sin(latitudeDifference / 2) * Math.sin(latitudeDifference / 2) +
        Math.cos(customerLatitudeRadians) * Math.cos(vendorLatitudeRadians) *
        Math.sin(longitudeDifference / 2) * Math.sin(longitudeDifference / 2);
    // Perhitungan sudut pusat (sudut antara dua titik pada permukaan bola yang diukur dari pusat bola) di mana atan2 mengembalikan nilai dalam radian dari dua variabel
    var centralAngle = 2 * Math.atan2(Math.sqrt(angularDistance), Math.sqrt(1 - angularDistance));

    // Jarak antara dua titik pada permukaan bola diukur dari jari-jari bumi dikali dengan sudut pusat
    var distance = earthRadiusKilometer * centralAngle;
    // Hasil jarak antar dua titik lokasi dalam kilometer dengan nilai hingga 2 desimal di belakang koma
    return distance = distance.toFixed(2);
}

function toRadians(degrees) {
    // Konversi derajat ke radian
    return degrees * (Math.PI / 180);
}

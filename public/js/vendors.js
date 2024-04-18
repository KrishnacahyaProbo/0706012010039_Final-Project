var userLat, userLng;
var currentPage = 1;
var perPage = 12;

document.addEventListener("DOMContentLoaded", function () {
    setVendorToMenu();
    getCurrentLocation();
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
        },
        dataType: 'json',
        success: function (response) {
            if ($("#searchInput").val() != '') {
                $.each(response.data, function (index, vendor) {
                    var cardCol = $('<div class="col d-flex"></div>');
                    var card = $('<div class="card gap-3 h-100"></div>');
                    var img = $('<img src="' + (vendor.profile_photo_url != null ? vendor.profile_photo_url : "") + '" alt="" class="card-img-top rounded-0" loading="lazy">');
                    var cardBody = $('<div class="card-body p-0"></div>');
                    var title = $('<h3 class="card-title"><a href="#" class="stretched-link">' + vendor.name + '</a></h3>');
                    var rating = $('<div class="d-grid text-secondary gap-1"><div class="d-flex gap-2"><i class="bi bi-star"></i><span class="card-text">' + vendor.rating + '/5</span></div></div>');
                    var address = $('<div class="d-flex gap-2"><i class="bi bi-geo-alt"></i><p class="card-text">' + vendor.address + ' - ' + calculateDistance(userLat, userLng, vendor.latitude, vendor.longitude) + 'km</p></div>');
                    var shipping = $('<div class="d-flex gap-2"><i class="bi bi-truck"></i><p class="card-text">Rp' + (vendor.delivery !== null ? formatRupiah(vendor.delivery.shipping_cost) : 0) + '</p></div>');

                    cardBody.append(title).append(rating).append(address).append(shipping);
                    card.append(img).append(cardBody);
                    cardCol.append(card);
                    vendorContainer.append(cardCol);

                    cardCol.click(function () {
                        // Get vendor_id from data attribute
                        var vendorId = $(this).data('vendor_id');
                        window.location.href = '/vendors/' + vendorId;
                    });

                    title.click(function () {
                        // Get vendor_id from parent cardCol's data attribute
                        var vendorId = $(this).closest('.col').data('vendor_id');
                        console.log('Clicked on title for vendor_id: ' + vendorId);
                    });
                });
            } else {
                $.each(response.data.data, function (index, vendor) {
                    var cardCol = $('<div class="col d-flex"></div>');
                    var card = $('<div class="card gap-3 h-100"></div>');
                    var img = $('<img src="' + (vendor.profile_photo_url != null ? vendor.profile_photo_url : "") + '" alt="" class="card-img-top rounded-0" loading="lazy">');
                    var cardBody = $('<div class="card-body p-0"></div>');
                    var title = $('<h3 class="card-title"><a href="#" class="stretched-link">' + vendor.name + '</a></h3>');
                    var rating = $('<div class="d-flex gap-2"><i class="bi bi-star"></i><span class="card-text">' + vendor.rating + '/5</span></div>');
                    var address = $('<div class="d-flex gap-2"><i class="bi bi-geo-alt"></i><p class="card-text">' + vendor.address + ' - ' + calculateDistance(userLat, userLng, vendor.latitude, vendor.longitude) + 'km</p></div>');
                    var shipping = $('<div class="d-flex gap-2"><i class="bi bi-truck"></i><p class="card-text">Rp' + (vendor.delivery !== null ? formatRupiah(vendor.delivery.shipping_cost) : 0) + '</p></div>');
                    cardCol.data('vendor_id', vendor.id);

                    cardBody.append(title).append(rating).append(address).append(shipping);
                    card.append(img).append(cardBody);
                    cardCol.append(card);
                    vendorContainer.append(cardCol);

                    cardCol.click(function () {
                        // Get vendor_id from data attribute
                        var vendorId = $(this).data('vendor_id');
                        window.location.href = '/vendors/' + vendorId;
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

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
                console.log("User's latitude:", userLat);
                console.log("User's longitude:", userLng);
            },
            function (error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.error("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.error("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        console.error("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        console.error("An unknown error occurred.");
                        break;
                }
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
}

function calculateDistance(userLat, userLng, vendorLat, vendorLng) {
    console.log("Calculating distance", userLat, userLng, vendorLat, vendorLng);
    var earthRadiusKm = 6371; // Earth radius in kilometers
    var userLatRadians = toRadians(userLat);
    var vendorLatRadians = toRadians(vendorLat);
    var latDiff = toRadians(vendorLat - userLat);
    var lngDiff = toRadians(vendorLng - userLng);

    var a = Math.sin(latDiff / 2) * Math.sin(latDiff / 2) +
        Math.cos(userLatRadians) * Math.cos(vendorLatRadians) *
        Math.sin(lngDiff / 2) * Math.sin(lngDiff / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    var distance = earthRadiusKm * c; // Distance in kilometers
    return distance = distance.toFixed(2);
}

function toRadians(degrees) {
    return degrees * (Math.PI / 180);
}

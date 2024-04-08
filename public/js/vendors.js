var userLat, userLng;
var currentPage = 1;
var perPage = 12;

document.addEventListener("DOMContentLoaded", function () {
    setVendorToMenu(); // Call your function here
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
            if($("#searchInput").val() != ''){
                $.each(response.data, function (index, vendor) {
                    var cardCol = $('<div class="col d-flex"></div>');
                    var card = $('<div class="card gap-3"></div>');
                    var img = $('<img src="' + (vendor.profile_photo_url != null ? vendor.profile_photo_url : "/images/no_foto.jpg") + '" alt="" class="card-img-top rounded-0" loading="lazy">');
                    var cardBody = $('<div class="card-body p-0"></div>');
                    var title = $('<h3 class="card-title"><a href="#" class="stretched-link">' + vendor.name + '</a></h3>');
                    var rating = $('<div class="d-grid text-secondary gap-1"><div class="d-flex gap-2"><i class="bi bi-star"></i><span class="card-text">' + vendor.rating + '/5</span></div></div>');
                    var address = $('<div class="d-flex gap-2"><i class="bi bi-geo-alt"></i><p class="card-text">' + vendor.address + ' - ' + calculateDistance(userLat, userLng, vendor.latitude, vendor.longitude) + 'km</p></div>');
                    var shipping = $('<div class="d-flex gap-2"><i class="bi bi-truck"></i><p class="card-text">Rp.' + (vendor.delivery !== null ? formatRupiah(vendor.delivery.shipping_cost) : 0) + '</p></div>');

                    cardBody.append(title).append(rating).append(address).append(shipping);
                    card.append(img).append(cardBody);
                    cardCol.append(card);
                    vendorContainer.append(cardCol);
                });
            }else{
                $.each(response.data.data, function (index, vendor) {
                    var cardCol = $('<div class="col d-flex"></div>');
                    var card = $('<div class="card gap-3"></div>');
                    var img = $('<img src="' + (vendor.profile_photo_url != null ? vendor.profile_photo_url : "/images/no_foto.jpg") + '" alt="" class="card-img-top rounded-0" loading="lazy">');
                    var cardBody = $('<div class="card-body p-0"></div>');
                    var title = $('<h3 class="card-title"><a href="#" class="stretched-link">' + vendor.name + '</a></h3>');
                    var rating = $('<div class="d-grid text-secondary gap-1"><div class="d-flex gap-2"><i class="bi bi-star"></i><span class="card-text">' + vendor.rating + '/5</span></div></div>');
                    var address = $('<div class="d-flex gap-2"><i class="bi bi-geo-alt"></i><p class="card-text">' + vendor.address + ' - ' + calculateDistance(userLat, userLng, vendor.latitude, vendor.longitude) + 'km</p></div>');
                    var shipping = $('<div class="d-flex gap-2"><i class="bi bi-truck"></i><p class="card-text">Rp.' + (vendor.delivery !== null ? formatRupiah(vendor.delivery.shipping_cost) : 0) + '</p></div>');

                    cardBody.append(title).append(rating).append(address).append(shipping);
                    card.append(img).append(cardBody);
                    cardCol.append(card);
                    vendorContainer.append(cardCol);
                });

                // Add pagination links
                if (response.data.next_page_url || response.data.prev_page_url) {
                    var paginationHtml = '<div class="pagination justify-content-center">' + // Add justify-content-center class here
                        '<ul class="pagination">';

                    // Previous button
                    if (response.data.prev_page_url) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="previousPage()">Previous</a></li>';
                    }

                    // Page numbers
                    for (var i = 1; i <= response.last_page; i++) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="goToPage(' + i + ')">' + i + '</a></li>';
                    }

                    // Next button
                    if (response.data.next_page_url) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="nextPage()">Next</a></li>';
                    }

                    paginationHtml += '</ul>' +
                        '</div>';

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
    currentPage = pageNumber; // Set current page to the clicked page number
    setVendorToMenu(); // Load vendors for the clicked page
}

// Function to handle click on Next button
function nextPage() {
    currentPage++; // Increment current page number
    setVendorToMenu(); // Load vendors for the next page
}

// Function to handle click on Previous button
function previousPage() {
    currentPage--; // Decrement current page number
    setVendorToMenu(); // Load vendors for the previous page
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

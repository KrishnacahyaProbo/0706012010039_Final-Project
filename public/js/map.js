function getLocation(valueAddress) {
    document.getElementById("permissionDenied").innerHTML = "";
    document.getElementById("map").style.display = "block";

    if (valueAddress == null) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    document.getElementById("latitude").value = latitude;
                    document.getElementById("longitude").value = longitude;

                    // Initialize Leaflet map
                    map = L.map("map").setView([latitude, longitude], 13);

                    // Add OpenStreetMap tile layer
                    L.tileLayer(
                        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "© OpenStreetMap contributors",
                    }
                    ).addTo(map);

                    // Add marker at user's location
                    var marker = L.marker([latitude, longitude], {
                        draggable: true,
                    })
                        .addTo(map)
                        .bindPopup(
                            "Latitude: " +
                            latitude +
                            "<br>Longitude: " +
                            longitude
                        )
                        .openPopup();

                    // Event listener for marker drag end
                    marker.on("dragend", function (event) {
                        var marker = event.target;
                        var position = marker.getLatLng();
                        var newLatitude = position.lat;
                        var newLongitude = position.lng;

                        // Update latitude and longitude input fields
                        document.getElementById("latitude").value = newLatitude;
                        document.getElementById("longitude").value = newLongitude;

                        // Update popup content with new latitude and longitude
                        marker.getPopup().setContent(
                            "Latitude: " + newLatitude + "<br>Longitude: " + newLongitude
                        );

                        // Get address for the new marker position
                        getAddress(newLatitude, newLongitude);
                    });

                    // Get address for the initial marker position
                    getAddress(latitude, longitude);
                },
                function (error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            console.error("Tidak dapat mendeteksi lokasi, mohon memberikan izin akses lokasi untuk pencarian posisi Anda saat ini.");
                            $('#permissionDenied').html('<div class="alert alert-warning" role="alert">Tidak dapat mendeteksi lokasi, mohon memberikan izin akses lokasi untuk pencarian posisi Anda saat ini.</div>');
                            document.getElementById("map").style.display = "none";
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
            alert("Peramban tidak dapat memfasilitasi pengambilan geolokasi.");
            console.log("Peramban tidak dapat memfasilitasi pengambilan geolokasi.");
        }
    } else {
        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + valueAddress)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var result = data[0];
                    console.log(map, "map");
                    if (typeof map !== "undefined") {
                        map.remove();
                    }
                    map = L.map("map");

                    // Add OpenStreetMap tile layer
                    L.tileLayer(
                        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "© OpenStreetMap contributors",
                    }
                    ).addTo(map);

                    // Add marker at user's location
                    var marker = L.marker([data[0].lat, data[0].lon], {
                        draggable: true,
                    })
                        .addTo(map)
                        .bindPopup(
                            "Latitude: " +
                            data[0].lat +
                            "<br>Longitude: " +
                            data[0].lon
                        )
                        .openPopup();

                    document.getElementById("latitude").value = data[0].lat;
                    document.getElementById("longitude").value = data[0].lon;
                    getAddress(data[0].lat, data[0].lon);

                    // Event listener for marker drag end
                    marker.on("dragend", function (event) {
                        var marker = event.target;
                        var position = marker.getLatLng();
                        var newLatitude = position.lat;
                        var newLongitude = position.lng;

                        // Update latitude and longitude input fields
                        document.getElementById("latitude").value = newLatitude;
                        document.getElementById("longitude").value = newLongitude;

                        // Get address for the new marker position
                        getAddress(newLatitude, newLongitude);
                    });

                    // Set initial view
                    map.setView([data[0].lat, data[0].lon], 13);
                } else {
                    alert("Alamat tidak ditemukan.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

function getAddress(latitude, longitude) {
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
            document.getElementById("address").innerHTML = address;
            document.getElementById("address_text").innerHTML = address;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

var map;
var marker;

// Function to initialize the map
// Event listener for search input
document.getElementById("searchInput").addEventListener("keyup", function () {
    var input = this.value;
    if (input.length >= 3) {
        // Fetch suggestions from geocoding service
        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + input)
            .then(response => response.json())
            .then(data => {
                var dropdown = document.getElementById("addressDropdown");
                dropdown.innerHTML = ""; // Clear previous suggestions
                if (data && data.length > 0) {
                    data.forEach(function (item) {
                        // Create dropdown items
                        var option = document.createElement("a");
                        option.classList.add("dropdown-item");
                        option.textContent = item.display_name;
                        option.href = "#";
                        option.onclick = function () {
                            // Remove active class from previously active item
                            var activeItem = dropdown.querySelector(".dropdown-item.active");
                            if (activeItem) {
                                activeItem.classList.remove("active");
                            }
                            // Set selected address to input field
                            document.getElementById("searchInput").value = item.display_name;
                            document.getElementById("selectedAddress").value = item.display_name;
                            // Set current item as active
                            option.classList.add("active");
                            dropdown.style.display = "none";
                            return false; // Prevent page from reloading
                        };
                        dropdown.appendChild(option);
                    });
                    dropdown.style.display = "block";
                } else {
                    dropdown.style.display = "none";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        document.getElementById("addressDropdown").style.display = "none";
    }
});

// Close dropdown when clicking outside
document.addEventListener("click", function (event) {
    if (!event.target.matches("#searchInput")) {
        var dropdown = document.getElementById("addressDropdown");
        var selectedAddress = dropdown.querySelector(".dropdown-item.active");
        if (selectedAddress) {
            var address = selectedAddress.textContent;
            getLocation(address);
            // Fetch the coordinates of the selected address
        } else {
            console.log("No active dropdown item selected");
        }
        dropdown.style.display = "none";
    }
});

function searchAddress() {
    var address = document.getElementById("searchInput").value;

    // Perform geocoding to get latitude and longitude
    $.get(
        "https://nominatim.openstreetmap.org/search?format=json&q=" + address,
        function (data) {
            if (data && data.length > 0) {
                var result = data[0];
                var formattedAddress = result.display_name;

                // Update selected address input field
                document.getElementById("selectedAddress").value = formattedAddress;

                // Update search input field with selected address
                document.getElementById("searchInput").value = formattedAddress;
            } else {
                alert("Alamat tidak ditemukan.");
            }
        }
    );
}

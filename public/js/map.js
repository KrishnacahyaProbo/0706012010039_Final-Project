// Mendapatkan lokasi berdasarkan alamat atau geolokasi
function getLocation(valueAddress) {
    document.getElementById("permissionDenied").innerHTML = "";
    document.getElementById("map").style.display = "block";

    if (valueAddress == null) {
        // Jika tidak ada alamat, gunakan geolokasi
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    document.getElementById("latitude").value = latitude;
                    document.getElementById("longitude").value = longitude;

                    // Inisialisasi peta Leaflet
                    initMap(latitude, longitude);

                    // Tambahkan marker di lokasi pengguna
                    addMarker(latitude, longitude);

                    // Dapatkan alamat berdasarkan koordinat awal
                    getAddress(latitude, longitude);
                },
                function (error) {
                    handleGeolocationError(error);
                }
            );
        } else {
            alert("Peramban tidak mendukung pengambilan geolokasi.");
        }
    } else {
        // Jika ada alamat, gunakan Nominatim untuk mendapatkan koordinat
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${valueAddress}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var result = data[0];
                    if (typeof map !== "undefined") {
                        map.remove();
                    }
                    var lat = result.lat;
                    var lon = result.lon;

                    // Inisialisasi peta Leaflet dengan koordinat dari alamat
                    initMap(lat, lon);

                    // Tambahkan marker di lokasi yang diperoleh dari alamat
                    addMarker(lat, lon);

                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lon;
                    getAddress(lat, lon);
                } else {
                    alert("Alamat tidak ditemukan.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

// Inisialisasi peta Leaflet
function initMap(latitude, longitude) {
    map = L.map("map", {
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: 'topleft',
        }
    }).setView([latitude, longitude], 16);

    // Tambahkan lapisan tile OpenStreetMap
    L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
        detectRetina: true,
    }).addTo(map);

    // Tambahkan kontrol untuk menemukan lokasi pengguna saat ini
    L.control.locate({
        position: 'topleft',
        locateOptions: {
            enableHighAccuracy: true,
        },
        strings: {
            title: "Gunakan lokasi terkini",
            popup: "",
        }
    }).addTo(map);
}

// Tambahkan marker pada peta dan tambahkan event listener untuk drag end
function addMarker(latitude, longitude) {
    var marker = L.marker([latitude, longitude], {
        draggable: true,
    })
        .addTo(map)
        .bindPopup(`Latitude: ${latitude}<br>Longitude: ${longitude}`)
        .openPopup();

    marker.on("dragend", function (event) {
        var position = event.target.getLatLng();
        document.getElementById("latitude").value = position.lat;
        document.getElementById("longitude").value = position.lng;
        marker.getPopup().setContent(
            `Latitude: ${position.lat}<br>Longitude: ${position.lng}`
        );
        getAddress(position.lat, position.lng);
    });
}

// Menangani kesalahan geolokasi
function handleGeolocationError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            console.error("Izin akses lokasi ditolak.");
            $('#permissionDenied').html('<div class="alert alert-warning d-grid gap-3 text-center" role="alert"><i class="bi bi-geo-alt-fill display-1"></i><span>Tidak dapat mendeteksi lokasi, mohon memberikan izin akses lokasi untuk pencarian posisi Anda saat ini.</span></div>');
            document.getElementById("map").style.display = "none";
            break;
        case error.POSITION_UNAVAILABLE:
            console.error("Informasi lokasi tidak tersedia.");
            break;
        case error.TIMEOUT:
            console.error("Permintaan lokasi pengguna telah habis waktu.");
            break;
        case error.UNKNOWN_ERROR:
            console.error("Terjadi kesalahan, mohon coba lagi.");
            break;
    }
}

// Mendapatkan alamat dari koordinat
function getAddress(latitude, longitude) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`)
        .then(response => response.json())
        .then(data => {
            var address = "";
            if (data.address) {
                address += data.address.road ? `${data.address.road}, ` : "";
                address += data.address.village ? `${data.address.village}, ` : "";
                address += data.address.municipality ? `${data.address.municipality}, ` : "";
                address += data.address.city ? `${data.address.city}, ` : "";
                address += data.address.state ? `${data.address.state}, ` : "";
                address += data.address.postcode ? `${data.address.postcode}.` : "";
            }
            document.getElementById("address").innerHTML = address;
            document.getElementById("address_text").innerHTML = address;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Inisialisasi variabel global untuk peta dan marker
var map;
var marker;

// Event listener untuk input pencarian
document.getElementById("searchInput").addEventListener("keyup", function () {
    var input = this.value;
    if (input.length >= 3) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${input}`)
            .then(response => response.json())
            .then(data => {
                var dropdown = document.getElementById("addressDropdown");
                dropdown.innerHTML = "";
                if (data && data.length > 0) {
                    data.forEach(function (item) {
                        var option = document.createElement("a");
                        option.classList.add("dropdown-item");
                        option.textContent = item.display_name;
                        option.href = "#";
                        option.onclick = function () {
                            var activeItem = dropdown.querySelector(".dropdown-item.active");
                            if (activeItem) {
                                activeItem.classList.remove("active");
                            }
                            document.getElementById("searchInput").value = item.display_name;
                            document.getElementById("selectedAddress").value = item.display_name;
                            option.classList.add("active");
                            dropdown.style.display = "none";
                            return false;
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

// Event listener untuk menutup dropdown saat klik di luar input
document.addEventListener("click", function (event) {
    if (!event.target.matches("#searchInput")) {
        var dropdown = document.getElementById("addressDropdown");
        var selectedAddress = dropdown.querySelector(".dropdown-item.active");
        if (selectedAddress) {
            var address = selectedAddress.textContent;
            getLocation(address);
        } else {
            console.log("Tidak ada item dropdown yang dipilih");
        }
        dropdown.style.display = "none";
    }
});

// Mencari alamat
function searchAddress() {
    var address = document.getElementById("searchInput").value;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${address}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                var result = data[0];
                var formattedAddress = result.display_name;

                document.getElementById("selectedAddress").value = formattedAddress;
                document.getElementById("searchInput").value = formattedAddress;
            } else {
                alert("Alamat tidak ditemukan.");
            }
        });
}

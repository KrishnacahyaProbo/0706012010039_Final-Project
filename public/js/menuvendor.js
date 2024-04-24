// Wrap your code inside a function
function initialize() {
    // Your getLocation function
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showDistance);
        } else {
            document.getElementById("distance-info").innerHTML =
                "Geolocation is not supported by this browser.";
        }
    }

    // Your showDistance function
    function showDistance(position) {
        var userLatitude = position.coords.latitude;
        var userLongitude = position.coords.longitude;
        var distance = calculateDistance(
            userLatitude,
            userLongitude,
            vendorData.latitude,
            vendorData.longitude
        );
        document.getElementById("distance-info").innerHTML =
            vendorData.address + " - " + distance + "km";
    }

    // Your calculateDistance and toRadians functions
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var earthRadius = 6371; // Radius of the Earth in kilometers
        var dLat = toRadians(lat2 - lat1);
        var dLon = toRadians(lon2 - lon1);
        var a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRadians(lat1)) *
            Math.cos(toRadians(lat2)) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var distance = earthRadius * c; // Distance in kilometers
        return distance.toFixed(2);
    }

    function toRadians(degrees) {
        return (degrees * Math.PI) / 180;
    }

    // Call getLocation function when the document is ready
    getLocation();

    var calendarEl = document.getElementById("calendar_menu");
    var calendar = new FullCalendar.Calendar(calendarEl, {
        // Konfigurasi FullCalendar
        themeSystem: "bootstrap5",
        headerToolbar: {
            left: "title",
            right: "today prev,next",
        },
        footerToolbar: {
            center: "dayGridMonth,dayGridWeek,dayGridDay,listWeek",
        },
        buttonText: {
            today: "Today",
            month: "Month",
            week: "Week",
            day: "Day",
            list: "List",
        },
        titleFormat: {
            year: "numeric",
            month: "short",
        },
        dateClick: function (info) {
            // Extract the clicked date
            var clickedDate = info.date;

            // Format the date (if needed)
            var formattedDate = formatDate(clickedDate); // Implement formatDate function if needed

            // Show the selected date
            $.ajax({
                url: "/menus/schedule", // Adjust the URL to match your server route
                type: "GET",
                data: {
                    date: formattedDate,
                    id: vendorData.id,
                },
                success: function (response) {
                    var container = document.getElementById("menuCart"); // Ganti 'your-container-id' dengan ID aktual kontainer Anda
                    container.innerHTML = "";
                    // Loop melalui setiap menu dan membuat kartu untuk setiap menu
                    response.data_menu.menus.forEach(function (menu) {
                        var divCard = document.createElement("div");
                        divCard.classList.add("card");

                        var divCardBody = document.createElement("div");
                        divCardBody.classList.add(
                            "card-body",
                            "d-grid",
                            "gap-3",
                            "p-0"
                        );

                        var img = document.createElement("img");
                        img.src = menu.image;
                        img.alt = "";
                        img.classList.add("card-img-top", "rounded-0");
                        img.setAttribute("loading", "lazy");

                        var h3 = document.createElement("h3");
                        h3.classList.add("card-title");
                        h3.textContent = menu.menu_name;

                        var small = document.createElement("small");
                        small.classList.add("card-text", "text-secondary");
                        small.textContent = menu.description;

                        // Ambil semua harga dari menu_detail
                        var prices = menu.menu_detail.map(function (
                            menuDetail
                        ) {
                            return menuDetail.price;
                        });

                        // Temukan nilai terendah dan tertinggi
                        var minPrice = Math.min(...prices);

                        var h5 = document.createElement("h5");
                        h5.textContent = "Rp" + formatRupiah(minPrice) + "/pcs";

                        var divPorsiContainer = document.createElement("div");
                        divPorsiContainer.classList.add(
                            "d-flex",
                            "align-items-center",
                            "gap-3"
                        );
                        var spanPorsi = document.createElement("span");
                        spanPorsi.textContent = "Porsi";
                        var divPorsiButtons = document.createElement("div");
                        divPorsiButtons.classList.add("d-flex");
                        // Ambil semua ukuran yang tersedia dari menu_detail
                        var porsiOptions = menu.menu_detail.map(function (
                            menuDetail
                        ) {
                            return menuDetail.size;
                        });

                        // Anda mungkin ingin menghapus duplikat jika ada
                        porsiOptions = porsiOptions.filter(function (
                            value,
                            index,
                            self
                        ) {
                            return self.indexOf(value) === index;
                        });

                        // Loop melalui setiap tombol porsi
                        // Loop melalui setiap tombol porsi
                        // Variable untuk menyimpan porsi yang dipilih
                        var selectedOption = null;

                        // Loop melalui setiap tombol porsi
                        porsiOptions.forEach(function (option) {
                            var button = document.createElement("button");
                            button.classList.add(
                                "btn",
                                "btn-outline-secondary",
                                "rounded-pill",
                                "mx-1",
                                "px-3"
                            );
                            button.type = "button";
                            button.textContent = option;

                            // Menambahkan event listener untuk menangani klik tombol porsi
                            button.addEventListener("click", function () {
                                // Memeriksa apakah tombol sudah dipilih atau tidak
                                var isSelected =
                                    button.classList.contains(
                                        "font-weight-bold"
                                    );

                                // Jika tombol belum dipilih dan belum ada porsi yang dipilih sebelumnya
                                if (!isSelected && selectedOption === null) {
                                    button.classList.add("font-weight-bold");
                                    button.style.backgroundColor = "lightblue";
                                    selectedOption = option;

                                    // Memperbarui harga sesuai dengan ukuran porsi yang dipilih
                                    var selectedMenuDetail =
                                        menu.menu_detail.find(function (
                                            menuDetail
                                        ) {
                                            return (
                                                menuDetail.size ===
                                                selectedOption
                                            );
                                        });
                                    if (selectedMenuDetail) {
                                        // Mengupdate harga sesuai dengan harga dari menu_detail yang dipilih
                                        var price = selectedMenuDetail.price;
                                        var formattedPrice =
                                            formatRupiah(price); // Format harga
                                        h5.textContent =
                                            "Rp" + formattedPrice + "/pcs";
                                    }
                                } else if (isSelected) {
                                    // Jika tombol sudah dipilih, maka hapus efek tebal dan warna latar belakangnya
                                    button.classList.remove("font-weight-bold");
                                    button.style.backgroundColor = "";
                                    selectedOption = null;

                                    // Mengembalikan harga ke harga asal jika porsi dibatalkan
                                    var originalPrice = minPrice; // Ganti dengan harga asli
                                    var formattedPrice =
                                        formatRupiah(originalPrice); // Format harga
                                    h5.textContent =
                                        "Rp" + formattedPrice + "/pcs";
                                } else {
                                    // Jika tombol belum dipilih dan ada porsi yang dipilih sebelumnya
                                    Swal.fire({
                                        icon: "question",
                                        title: "Anda telah memilih porsi pada menu ini. Apakah Anda ingin menambahkan ke keranjang belanja terlebih dahulu sebelum memilih porsi yang lainnya?",
                                        showDenyButton: true,
                                        showCancelButton: true,
                                        confirmButtonText: `Tambah Porsi Baru`,
                                        denyButtonText: `Batal`,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            var currentQuantity = parseInt(
                                                spanQuantity.textContent
                                            );
                                            console.log(currentQuantity);
                                            if (currentQuantity !== 0) {
                                                // Jika sudah dipilih, lakukan proses yang diperlukan
                                                // Misalnya, tambahkan item ke keranjang belanja
                                                addToCart();
                                            } else if (currentQuantity == 0) {
                                                // Tampilkan peringatan jika kuantitas belum dipilih
                                                Swal.fire({
                                                    icon: "warning",
                                                    title: "Anda harus memilih jumlah pembelian sebelum menambah porsi!",
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                            } else {
                                                // Menghapus latar belakang dan efek tebal dari porsi sebelumnya
                                                divPorsiButtons
                                                    .querySelectorAll("button")
                                                    .forEach(function (btn) {
                                                        btn.classList.remove(
                                                            "font-weight-bold"
                                                        );
                                                        btn.style.backgroundColor =
                                                            "";
                                                    });

                                                button.classList.add(
                                                    "font-weight-bold"
                                                );
                                                button.style.backgroundColor =
                                                    "lightblue";
                                                selectedOption = option;

                                                // Memperbarui harga sesuai dengan ukuran porsi yang dipilih
                                                var selectedMenuDetail =
                                                    menu.menu_detail.find(function (
                                                        menuDetail
                                                    ) {
                                                        return (
                                                            menuDetail.size ===
                                                            selectedOption
                                                        );
                                                    });
                                                if (selectedMenuDetail) {
                                                    // Mengupdate harga sesuai dengan harga dari menu_detail yang dipilih
                                                    var price =
                                                        selectedMenuDetail.price;
                                                    var formattedPrice =
                                                        formatRupiah(price); // Format harga
                                                    h5.textContent =
                                                        "Rp" +
                                                        formattedPrice +
                                                        "/pcs";
                                                }
                                            }

                                        }
                                    });
                                }
                            });

                            divPorsiButtons.appendChild(button);
                        });

                        divPorsiContainer.appendChild(spanPorsi);
                        divPorsiContainer.appendChild(divPorsiButtons);

                        divPorsiContainer.appendChild(spanPorsi);
                        divPorsiContainer.appendChild(divPorsiButtons);

                        var divKuantitasContainer =
                            document.createElement("div");
                        divKuantitasContainer.classList.add(
                            "d-flex",
                            "align-items-center",
                            "gap-3"
                        );
                        var spanKuantitas = document.createElement("span");
                        spanKuantitas.textContent = "Kuantitas";
                        var divKuantitasControls =
                            document.createElement("div");
                        divKuantitasControls.classList.add(
                            "d-flex",
                            "align-items-center",
                            "border-secondary",
                            "rounded",
                            "border"
                        );
                        var buttonMinus = document.createElement("button");
                        buttonMinus.classList.add("btn", "border-0");
                        var iMinus = document.createElement("i");
                        iMinus.classList.add(
                            "bi",
                            "bi-dash-lg",
                            "text-primary"
                        );
                        buttonMinus.appendChild(iMinus);

                        //quantity
                        var spanQuantity = document.createElement("span");
                        spanQuantity.classList.add("mx-2");
                        spanQuantity.textContent = "0";
                        var buttonPlus = document.createElement("button");
                        buttonPlus.classList.add("btn", "border-0");
                        var iPlus = document.createElement("i");
                        iPlus.classList.add("bi", "bi-plus-lg", "text-primary");
                        buttonPlus.appendChild(iPlus);
                        divKuantitasControls.appendChild(buttonMinus);
                        divKuantitasControls.appendChild(spanQuantity);
                        divKuantitasControls.appendChild(buttonPlus);
                        divKuantitasContainer.appendChild(spanKuantitas);
                        divKuantitasContainer.appendChild(divKuantitasControls);
                        //quantity

                        //add to cart button
                        var addToCartButton = document.createElement("button");
                        addToCartButton.classList.add(
                            "w-100",
                            "btn",
                            "btn-primary"
                        );
                        addToCartButton.textContent = "Add to Cart";
                        //add to cart button

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
                        container.appendChild(divCard);

                        buttonPlus.addEventListener("click", function () {
                            // Mendapatkan nilai quantity saat ini
                            var currentQuantity = parseInt(
                                spanQuantity.textContent
                            );

                            // Menambahkan 1 ke nilai quantity
                            var newQuantity = currentQuantity + 1;

                            // Memperbarui teks pada elemen spanQuantity dengan nilai quantity yang baru
                            spanQuantity.textContent = newQuantity;
                        });

                        buttonMinus.addEventListener("click", function () {
                            // Mendapatkan nilai quantity saat ini
                            var currentQuantity = parseInt(
                                spanQuantity.textContent
                            );

                            // Pastikan nilai quantity tidak kurang dari 0
                            if (currentQuantity > 0) {
                                // Mengurangi 1 dari nilai quantity
                                var newQuantity = currentQuantity - 1;

                                // Memperbarui teks pada elemen spanQuantity dengan nilai quantity yang baru
                                spanQuantity.textContent = newQuantity;
                            }
                        });

                        // Menambahkan event listener untuk menangani klik tombol "Add to Cart"
                        addToCartButton.addEventListener("click", function () {
                            // Memeriksa apakah ada tombol porsi yang dipilih
                            var selectedPorsi =
                                divPorsiButtons.querySelector(
                                    ".font-weight-bold"
                                );
                            var currentQuantity = parseInt(
                                spanQuantity.textContent
                            );

                            // Jika tidak ada yang dipilih, tampilkan pesan toast
                            if (!selectedPorsi) {
                                // Tampilkan pesan toast menggunakan SweetAlert
                                Swal.fire({
                                    title: "Pilih ukuran porsi terlebih dahulu!",
                                    icon: "warning",
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            } else if (currentQuantity === 0) {
                                // Jika nilai quantity nol, tampilkan pesan toast
                                Swal.fire({
                                    title: "Silahkan input jumlah pembelian untuk menu ini!",
                                    icon: "warning",
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            } else {
                                // Jika ada yang dipilih, lakukan sesuatu, misalnya tambahkan item ke keranjang belanja
                                // atau tampilkan pesan konfirmasi
                                // Contoh: Tampilkan pesan konfirmasi menggunakan SweetAlert
                                Swal.fire({
                                    title: "Item berhasil ditambahkan ke keranjang!",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            }
                        });
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching menu schedules:", error);

                    // Call failureCallback with an empty array
                    failureCallback([]);
                },
            });
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            var events = [];
            var eventColor = "#842029";

            // Mengonversi data jadwal menjadi objek acara dan menambahkannya ke dalam daftar acara
            vendorData.menu.forEach(function (menuItem) {
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

        // Menangani event ketika event di-drop (dragged)
        eventDrop: function (arg) {
            var eventId = arg.event.id; // ID dari event yang di-drop
            var newStart = arg.event.start; // Tanggal baru setelah di-drop
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            // Create a new Date object
            var date = new Date(newStart);

            // Extract year, month, and day components
            var year = date.getFullYear();
            // JavaScript months are 0-based, so we add 1 to get the correct month
            var month = (date.getMonth() + 1).toString().padStart(2, "0");
            var day = date.getDate().toString().padStart(2, "0");

            // Form the yyyy-mm-dd format
            var formattedDate = year + "-" + month + "-" + day;
            // Perform AJAX request untuk update tanggal event
            $.ajax({
                url: "schedules/update",
                type: "POST",
                data: {
                    id: eventId,
                    new_start: formattedDate,
                    _token: csrfToken,
                },
                success: function (response) {
                    console.log("vendor", response);

                    if (response.success) {
                        $("#mdlForm").modal("hide");
                        $("#mdlFormContent").html("");
                        setTimeout(function () {
                            showDetail(id);
                        }, 1000);
                    } else {
                        alert("Event date update failed. Please try again.");
                    }
                },
                error: function (xhr, status, error) {
                    alert(
                        "Event date update failed. Please try again.",
                        xhr.responseJSON.error
                    );
                },
            });
        },

        // Menangani event ketika event di-resize
        eventResize: function (arg) {
            var eventId = arg.event.id; // ID dari event yang di-resize
            var newEnd = arg.event.end; // Tanggal baru setelah di-resize
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            // Perform AJAX request untuk update tanggal event
            $.ajax({
                url: "schedules/update",
                type: "PUT",
                data: {
                    id: eventId,
                    new_end: newEnd.format(), // Format tanggal baru sesuai kebutuhan Anda
                    _token: csrfToken,
                },
                success: function (response) {
                    console.log("Event date updated successfully");
                },
                error: function (xhr, status, error) {
                    console.error("Error updating event date:", error);
                },
            });
        },
    });
    calendar.render();
}

function formatDate(date) {
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, "0");
    var day = date.getDate().toString().padStart(2, "0");
    return year + "-" + month + "-" + day;
}

// Call initialize function when the document is loaded
document.addEventListener("DOMContentLoaded", initialize);

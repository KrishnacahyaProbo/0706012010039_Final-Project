var menuDate = null;

function initialize() {
    var status = 'success';
    var previousSelectedOption = null;
    var choosing = 0;

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showDistance);
        } else {
            document.getElementById("distance-info").innerHTML =
                "Geolocation is not supported by this browser.";
        }
    }

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

    getLocation();

    var calendarEl = document.getElementById("calendar_menu");
    var calendar = new FullCalendar.Calendar(calendarEl, {
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

            // Format the date
            var formattedDate = formatDate(clickedDate);

            // Show the selected date
            $.ajax({
                url: "/menus/schedule",
                type: "GET",
                data: {
                    date: formattedDate,
                    id: vendorData.id,
                },
                success: function (response) {
                    menuDate = response.data_menu.schedule;
                    var container = document.getElementById("menuCart");
                    container.innerHTML = "";
                    response.data_menu.menus.forEach(function (menu) {
                        var divCard = document.createElement("div");
                        divCard.classList.add("card");

                        var divCardBody = document.createElement("div");
                        divCardBody.classList.add(
                            "card-body",
                            "d-grid",
                            "gap-3"
                        );

                        var img = document.createElement("img");
                        img.src = menu.image.includes('http') ? menu.image : public + 'menu/' + menu.image;
                        img.alt = "";
                        img.classList.add("rounded-1", "w-50");
                        img.setAttribute("loading", "lazy");

                        var h3 = document.createElement("h3");
                        h3.classList.add("card-title");
                        h3.textContent = menu.menu_name;

                        var type = document.createElement("span");
                        type.classList.add("card-text");

                        if (menu.type === "spicy") {
                            var span = document.createElement("span");
                            span.classList.add("badge", "rounded-pill", "text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle");
                            span.textContent = "Pedas";
                            type.appendChild(span);
                        } else if (menu.type === "no_spicy") {
                            var span = document.createElement("span");
                            span.classList.add("badge", "rounded-pill", "text-primary-emphasis", "bg-primary-subtle", "border", "border-primary-subtle");
                            span.textContent = "Tidak Pedas";
                            type.appendChild(span);
                        }

                        var small = document.createElement("small");
                        small.classList.add("card-text", "text-secondary");
                        small.textContent = menu.description;

                        // Ambil semua harga dari menu_detail
                        var prices = menu.menu_detail.map(function (menuDetail) {
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
                        var porsiOptions = menu.menu_detail.map(function (menuDetail) {
                            return menuDetail.size;
                        });

                        // Menghapus duplikat jika ada
                        porsiOptions = porsiOptions.filter(function (value, index, self) {
                            return self.indexOf(value) === index;
                        });

                        // Variable untuk menyimpan porsi yang dipilih
                        var selectedOption = null;
                        var firstIteration = true;

                        // Loop melalui setiap tombol porsi
                        previousSelectedOption = null;
                        porsiOptions.forEach(function (option) {
                            var button = document.createElement("button");
                            button.classList.add(
                                "btn",
                                "btn-outline-primary",
                                "rounded-pill",
                                "mx-1",
                                "px-3"
                            );
                            button.type = "button";
                            button.textContent = option;

                            // Set the background color for the previously selected option
                            if (previousSelectedOption === option) {
                                button.classList.add("font-weight-bold");
                                button.style.backgroundColor = "#842029";
                                button.style.color = "white";
                            }

                            // Menambahkan event listener untuk menangani klik tombol porsi
                            button.addEventListener("click", function () {
                                // Memeriksa apakah tombol sudah dipilih atau tidak
                                var isSelected = button.classList.contains("font-weight-bold");

                                // Menghapus efek tebal dan warna latar belakang dari porsi sebelumnya
                                divPorsiButtons
                                    .querySelectorAll("button")
                                    .forEach(function (btn) {
                                        btn.classList.remove("font-weight-bold");
                                        btn.style.backgroundColor = "";
                                        btn.style.color = "";
                                    });

                                // Jika tombol belum dipilih dan belum ada porsi yang dipilih sebelumnya
                                if (!isSelected && selectedOption === null) {
                                    button.classList.add("font-weight-bold");
                                    button.style.backgroundColor = "#842029";
                                    button.style.color = "white";
                                    selectedOption = option;
                                    previousSelectedOption = option;

                                    // Memperbarui harga sesuai dengan ukuran porsi yang dipilih
                                    var selectedMenuDetail =
                                        menu.menu_detail.find(function (menuDetail) {
                                            return (menuDetail.size === selectedOption);
                                        });
                                    if (selectedMenuDetail) {
                                        // Mengubah harga sesuai dengan harga dari menu_detail yang dipilih
                                        var price = selectedMenuDetail.price;
                                        var formattedPrice = formatRupiah(price);
                                        h5.textContent = "Rp" + formattedPrice + "/pcs";
                                    }
                                } else if (isSelected) {
                                    button.classList.remove("font-weight-bold");
                                    button.style.backgroundColor = "";
                                    button.style.color = "";
                                    selectedOption = null;

                                    // Mengembalikan harga ke harga asal jika porsi dibatalkan
                                    var originalPrice = minPrice;
                                    var formattedPrice = formatRupiah(originalPrice);
                                    h5.textContent = "Rp" + formattedPrice + "/pcs";
                                } else {
                                    // Jika tombol belum dipilih dan ada porsi yang dipilih sebelumnya
                                    Swal.fire({
                                        icon: "question",
                                        title: "Anda telah memilih porsi pada menu ini. Apakah Anda ingin menambahkan ke keranjang belanja terlebih dahulu sebelum memilih porsi yang lainnya?",
                                        showCancelButton: true,
                                        confirmButtonText: `Tambah Porsi Baru`,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            var currentQuantity = parseInt(spanQuantity.textContent);
                                            console.log(currentQuantity);
                                            if (currentQuantity !== 0) {
                                                var menuId = menu.id;
                                                var selectedPorsiText = option;
                                                console.log(
                                                    menuId,
                                                    previousSelectedOption,
                                                    "menuId, selectedPorsiText"
                                                );
                                                addToCart(menuId, currentQuantity, button, selectedPorsiText);
                                            } else if (currentQuantity == 0) {
                                                // Tampilkan peringatan jika kuantitas belum dipilih
                                                Swal.fire({
                                                    icon: "warning",
                                                    title: "Anda harus memilih jumlah pembelian sebelum menambah porsi!",
                                                    showConfirmButton: false,
                                                    timer: 2000,
                                                });
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

                        var addToCartButton = document.createElement("button");
                        addToCartButton.classList.add(
                            "w-100",
                            "btn",
                            "btn-primary"
                        );
                        addToCartButton.textContent = "Add to Cart";

                        divCardBody.appendChild(img);
                        divCardBody.appendChild(h3);
                        divCardBody.appendChild(type);
                        divCardBody.appendChild(small);
                        divCardBody.appendChild(h5);
                        divCardBody.appendChild(divPorsiContainer);
                        divCardBody.appendChild(divKuantitasContainer);
                        divCardBody.appendChild(addToCartButton);

                        divCard.appendChild(divCardBody);

                        container.appendChild(divCard);

                        buttonPlus.addEventListener("click", function () {
                            // Mendapatkan nilai quantity saat ini
                            var currentQuantity = parseInt(spanQuantity.textContent);

                            // Menambahkan 1 ke nilai quantity
                            var newQuantity = currentQuantity + 1;

                            // Memperbarui teks pada elemen spanQuantity dengan nilai quantity yang baru
                            spanQuantity.textContent = newQuantity;
                        });

                        buttonMinus.addEventListener("click", function () {
                            // Mendapatkan nilai quantity saat ini
                            var currentQuantity = parseInt(spanQuantity.textContent);

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
                            var selectedPorsi = divPorsiButtons.querySelector(".font-weight-bold");
                            var currentQuantity = parseInt(spanQuantity.textContent);

                            // Jika tidak ada yang dipilih, tampilkan pesan toast
                            if (!selectedPorsi) {
                                Swal.fire({
                                    title: "Pilih ukuran porsi terlebih dahulu!",
                                    icon: "warning",
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            } else if (currentQuantity === 0) {
                                // Jika nilai quantity nol, tampilkan pesan toast
                                Swal.fire({
                                    title: "Silakan input jumlah pembelian untuk menu ini!",
                                    icon: "warning",
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            } else {
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
    });
    calendar.render();
}

function addToCart(menuId, currentQuantity, button, selectedPorsiText) {
    Swal.fire({
        title: 'Add to Cart',
        html: '<textarea id="swal-textarea" placeholder="Please enter additional notes..." style="width: 100%;"></textarea>',
        showDenyButton: true,
        confirmButtonText: 'Add',
        denyButtonText: 'Cancel',
        preConfirm: () => {
            // Retrieve the value from the text area
            return document.getElementById('swal-textarea').value.trim();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // The user clicked the confirm button
            const notes = result.value;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/carts/store',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    menuId: menuId,
                    menuDate: menuDate,
                    previousSelectedOption: selectedPorsiText,
                    currentQuantity: currentQuantity,
                    notes: notes
                },
                success: function (response) {
                    // Handle success response
                    toastr.success('Item added to cart successfully');
                    button.classList.add("font-weight-bold");
                    button.style.backgroundColor = "#842029";
                    button.style.color = "white";
                    previousSelectedOption = selectedPorsiText;
                },
                error: function (xhr, status, error) {
                    // Handle error
                    toastr.error('Error adding item to cart');
                }
            });
        } else if (result.isDenied) {
            // The user clicked the cancel button
            const notes = result.value;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                success: function (response) {
                    console.log('Add to cart canceled', response);
                    button.classList.add("font-weight-bold");
                    button.style.backgroundColor = "#842029";
                    button.style.color = "white";
                },
                error: function (xhr, status, error) {
                    // Handle error
                    toastr.error('Error adding item to cart');
                }
            });
        }
    });
}

function destroy(id) {
    var confirmation = window.confirm("Yakin ingin hapus item keranjang belanja?");
    if (confirmation) {
        $.ajax({
            url: "/carts/destroy",
            method: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                id: id
            },
            success: function (response) {
                window.location.reload();
                toastr.success("Cart item deleted successfully");
                fetchDataMenuItem();
            },
            error: function (xhr, status, error) {
                toastr.error("Error deleting Cart item:", error);
            },
        });
    }
}

function formatDate(date) {
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, "0");
    var day = date.getDate().toString().padStart(2, "0");
    return year + "-" + month + "-" + day;
}

// Call initialize function when the document is loaded
document.addEventListener("DOMContentLoaded", initialize);

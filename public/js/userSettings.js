$(document).ready(function () {
    fetchDataAddress();
});

function fetchDataAddress() {
    $.ajax({
        url: '/settings/data',
        type: 'GET',
        success: function (data) {
            if (data.user_setting != null) {
                getLocation(data.user_setting.address);
                $("#latitude").val(data.user_setting.latitude);
                $("#longitude").val(data.user_setting.longitude);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error: ', error);
        }
    });
}

function aboutSetting() {
    var formData = $('#about').serialize();

    $.ajax({
        url: '/settings/about',
        type: 'POST',
        data: formData,
        success: function (response) {
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error: ', error);
        }
    });
}

function balanceSetting() {
    var formData = $('#rekeningUser').serialize();

    $.ajax({
        url: '/balance/settings',
        type: 'POST',
        data: formData,
        success: function (response) {
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error: ', error);
        }
    });
}

function deliverySetting() {
    var formData = $('#pengiriman').serialize();

    $.ajax({
        url: '/delivery/settings',
        type: 'POST',
        data: formData,
        success: function (response) {
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error: ', error);
        }
    });
}

function setPesanan() {
    var formDataPemesanan = $('#pesanan').serialize();

    $.ajax({
        url: '/settings/order',
        type: 'POST',
        data: formDataPemesanan,
        success: function (response) {
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error: ', error);
        }
    });
}

function setAlamat() {
    // Serialize both form data
    var formDataAlamat = $('#alamat').serialize();
    var formDataPemesanan = $('#pesanan').serialize();

    // Merge both form data into one object
    var combinedFormData = formDataAlamat + '&' + formDataPemesanan;

    $.ajax({
        url: '/settings/order',
        type: 'POST',
        data: combinedFormData,
        success: function (response) {
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error: ', error);
        }
    });
}

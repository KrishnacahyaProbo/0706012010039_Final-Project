$(document).ready(function () {
    fetchDataAddress();
});

function fetchDataAddress() {
    $.ajax({
        url: '/users/getDataSettings',
        type: 'GET',
        success: function (data) {
            if (data.user_setting != null) {
                getLocation(data.user_setting.address);
                $("#latitude").val(data.user_setting.latitude);
                $("#longitude").val(data.user_setting.longitude);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error retrieving data:', error);
        }
    });
}

function balanceSettings() {
    var formData = $('#rekeningUser').serialize();
    $.ajax({
        url: '/users/balanceSettings',
        type: 'POST',
        data: formData,
        success: function (response) {
            window.location.href = '/users/settings';
        },
        error: function (xhr, status, error) {
            console.error('Error submitting data:', error);
        }
    });
}

function settingsDelivery() {
    var formData = $('#pengiriman').serialize();
    $.ajax({
        url: '/users/settingsDelivery',
        type: 'POST',
        data: formData,
        success: function (response) {
            window.location.href = '/users/settings';
        },
        error: function (xhr, status, error) {
            console.error('Error submitting data:', error);
        }
    });
}

function userSettings() {
    // Serialize both form data
    var formDataAlamat = $('#alamat').serialize();
    var formDataPemesanan = $('#pesanan').serialize();

    // Merge both form data into one object
    var combinedFormData = formDataAlamat + '&' + formDataPemesanan;
    $.ajax({
        url: '/users/settingsPemesanan',
        type: 'POST',
        data: combinedFormData,
        success: function (response) {
            window.location.href = '/users/settings';
        },
        error: function (xhr, status, error) {
            console.error('Error submitting data:', error);
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
        url: '/users/settingsPemesanan',
        type: 'POST',
        data: combinedFormData,
        success: function (response) {
            window.location.href = '/users/settings';
        },
        error: function (xhr, status, error) {
            console.error('Error submitting data:', error);
        }
    });
}
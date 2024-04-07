$(document).ready(function() {
    // Assuming $user_setting->address is set somewhere in your Blade template or JavaScript code
    fetchDataAddress();
});

function fetchDataAddress(){
    $.ajax({
        url: '/users/getDataSettings',
        type: 'GET',
        success: function(data) {
            if(data.user_setting != null){
                getLocation(data.user_setting.address);
                $("#latitude").val(data.user_setting.latitude);
                $("#longitude").val(data.user_setting.longitude);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error retrieving data:', error);
            // Handle error response
        }
    });
}

function settingsDelivery(){
    var formData = $('#pengiriman').serialize(); // Serialize form data
    $.ajax({
        url: '/users/settingsDelivery', // Laravel route to handle the AJAX request
        type: 'POST',
        data: formData, // Send serialized form data
        success: function(response) {
            window.location.href = '/users/settings';
            // Optionally, you can redirect the user or show a success message here
        },
        error: function(xhr, status, error) {
            console.error('Error submitting data:', error);
            // Handle error response
        }
    });
}


function userSettings(){
      // Serialize both form data
      var formDataAlamat = $('#alamat').serialize();
      var formDataPemesanan = $('#pesanan').serialize();

      // Merge both form data into one object
      var combinedFormData = formDataAlamat + '&' + formDataPemesanan;// Serialize form data
    $.ajax({
        url: '/users/settingsPemesanan', // Laravel route to handle the AJAX request
        type: 'POST',
        data: combinedFormData, // Send serialized form data
        success: function(response) {
            window.location.href = '/users/settings';
            // Optionally, you can redirect the user or show a success message here
        },
        error: function(xhr, status, error) {
            console.error('Error submitting data:', error);
            // Handle error response
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
        url: '/users/settingsPemesanan', // Laravel route to handle the AJAX request
        type: 'POST',
        data: combinedFormData, // Send combined form data
        success: function(response) {
            window.location.href = '/users/settings';
            // Optionally, you can redirect the user or show a success message here
        },
        error: function(xhr, status, error) {
            console.error('Error submitting data:', error);
            // Handle error response
        }
    });
}



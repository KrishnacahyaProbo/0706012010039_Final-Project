$(document).ready(function () {
    // Submit handler for pengiriman form
    $('#pengiriman').submit(function (e) {
        
        e.preventDefault(); 
        console.lof("masuk sini");
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
            url: '/users/settings/delivery', // Specify your API endpoint
            type: 'POST',
            data: formData, // Send serialized form data
            success: function (response) {
                console.log('Pengiriman data submitted successfully');
                // Handle success response
            },
            error: function (xhr, status, error) {
                console.error('Error submitting pengiriman data:', error);
                // Handle error response
            }
        });
    });

    // Similar submit handlers for other forms (pesanan and alamat)
    // Follow the same pattern as above for other forms
});
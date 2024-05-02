$(document).ready(function () {
    $('.btn-increment').click(function (e) {
        e.preventDefault();

        var $input = $(this).closest('.d-flex').find('.qty-input');
        var value = parseInt($input.val(), 10);
        value = isNaN(value) ? 0 : value;
        if (value < 999) {
            $input.val(value + 1);
        }
    });

    $('.btn-decrement').click(function (e) {
        e.preventDefault();

        var $input = $(this).closest('.d-flex').find('.qty-input');
        var value = parseInt($input.val(), 10);
        value = isNaN(value) ? 0 : value;
        if (value > 1) {
            $input.val(value - 1);
        }
    });
});

$(document).on("click", '.btn-portion', function () {
    $('.portion').removeClass('portion');
    $(this).addClass('portion');
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.row-item').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-item').find('.portion').text();
    var price = $(this).closest('.row-item').find('#price').val();
    var quantity = $(this).closest('.row-item').find('#quantity').val();
    updateCart(ringkasanBelanja, indexItem, 1, cart_menu_id, portion, price, quantity);
    console.log(cart_menu_id, portion, price, quantity);
})

$(document).on("click", '.btn-increment', function () {
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.d-grid').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-item').find('.portion').text();
    var price = $(this).closest('.d-grid').find('#price').val();
    var quantity = $(this).closest('.d-grid').find('#quantity').val();
    updateCart(null, null, 0, cart_menu_id, portion, price, quantity);
})

$(document).on("click", '.btn-decrement', function () {
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.d-grid').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-item').find('.portion').text();
    var price = $(this).closest('.d-grid').find('#price').val();
    var quantity = $(this).closest('.d-grid').find('#quantity').val();
    updateCart(null, null, 0, cart_menu_id, portion, price, quantity);
})

function updateCart(ringkasanBelanja = null, indexItem = null, editButton = 0, cart_menu_id, portion, price,
    quantity) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/carts/update',
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            cart_menu_id: cart_menu_id,
            portion: portion,
            price: price,
            quantity: quantity
        },
        success: function (response) {
            if (editButton == 1) {
                var newHarga = 0;

                data[ringkasanBelanja]['items'][indexItem]['menu']['menu_detail'].forEach(element => {
                    if (element.size == portion) {
                        newHarga = element.price;
                    }
                });
                console.log(newHarga);
                $(`#newHarga${ringkasanBelanja + indexItem}`).html(
                    `Rp${newHarga.toLocaleString()}/pcs`);
            }
            // toastr.success('Cart item updated successfully');
        },
        error: function (xhr, status, error) {
            toastr.error('Error updating cart item');
        }
    });
}

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
    $(this).closest('.row-button-portion').find('.portion').removeClass('portion');
    $(this).addClass('portion');
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.row-per-item').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-per-item').find('.portion').text();
    var price = $(this).closest('.row-per-item').find('#price').val();
    var quantity = $(this).closest('.row-per-item').find('#quantity').val();
    var note = $(this).closest('.row-per-item').find('#note').val();
    updateCart(ringkasanBelanja, indexItem, 1, cart_menu_id, portion, price, quantity, note);
    console.log(cart_menu_id, portion, price, quantity, note);
})

$(document).on("click", '.btn-increment', function () {
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.row-per-item').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-per-item').find('.portion').text();
    var price = $(this).closest('.row-per-item').find('#price').val();
    var quantity = $(this).closest('.row-per-item').find('#quantity').val();
    var note = $(this).closest('.row-per-item').find('#note').val();
    updateCart(null, null, 0, cart_menu_id, portion, price, quantity, note);
})

$(document).on("click", '.btn-decrement', function () {
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.row-per-item').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-per-item').find('.portion').text();
    var price = $(this).closest('.row-per-item').find('#price').val();
    var quantity = $(this).closest('.row-per-item').find('#quantity').val();
    var note = $(this).closest('.row-per-item').find('#note').val();
    updateCart(null, null, 0, cart_menu_id, portion, price, quantity, note);
})

$(document).on("change", '.note', function () {
    var ringkasanBelanja = $(this).attr('ringkasanBelanja');
    var indexItem = $(this).attr('indexItem');
    var cart_menu_id = $(this).closest('.row-per-item').find('#cart_menu_id').val();
    var portion = $(this).closest('.row-per-item').find('.portion').text();
    var price = $(this).closest('.row-per-item').find('#price').val();
    var quantity = $(this).closest('.row-per-item').find('#quantity').val();
    var note = $(this).val();
    updateCart(null, null, 0, cart_menu_id, portion, price, quantity, note);
})

function updateCart(ringkasanBelanja = null, indexItem = null, editButton = 0, cart_menu_id, portion, price,
    quantity, note = null) {
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
            quantity: quantity,
            note: note
        },
        success: function (response) {
            if (editButton == 1) {
                var newPrice = 0;

                data[ringkasanBelanja]['items'][indexItem]['menu']['menu_detail'].forEach(element => {
                    if (element.size == portion) {
                        newPrice = element.price;
                    }
                });
                console.log(newPrice);
                $(`#newPrice${ringkasanBelanja + indexItem}`).attr(`price`, `${newPrice}`);
                $(`#newPrice${ringkasanBelanja + indexItem}`).html(`Rp${formatRupiah(newPrice)}/pcs`);
            }
            calculateCart();
            // toastr.success('Berhasil memperbarui item.');
        },
        error: function (xhr, status, error) {
            toastr.error('Gagal memperbarui item. ', error);
        }
    });
}

// Auto refresh Ringkasan Belanja jika terjadi perubahan porsi ataupun kuantitas
function calculateCart() {
    var totalCartRingkasanBelanja = 0;

    data.forEach(element => {
        var total = 0;

        $('.price_per_item').each(function (index) {
            if ($(this).attr('vendor') == element.name) {
                var cartQuantity = $('.total_cart_per_input').eq(index).val();
                total += parseInt($(this).attr('price')) * cartQuantity;
            }
        });

        element.price = total;
        $('.total_cart_per_vendor').each(function (index) {
            if ($(this).attr('vendor') == element.name) {
                $(this).html(`Rp${formatRupiah(total)}`);
            }
        });

        totalCartRingkasanBelanja += total;
    });
    $('#total_cart_ringkasan_belanja').text(`Rp${formatRupiah(totalCartRingkasanBelanja)}`);
}

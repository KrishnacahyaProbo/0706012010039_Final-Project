// Mencegah input Nominal yang melebihi balanceNominal
document.addEventListener("DOMContentLoaded", function () {
    // Mendapatkan elemen input Nominal
    var creditInput = document.getElementById('credit_cash_out');

    // Mengatur nilai maksimum input Nominal berdasarkan nilai balanceNominal
    creditInput.max = balanceNominal;
});

var vendorCategory = document.getElementById('vendor_category')
var vendorCategoryValue = document.getElementById('vendor_category_value')

vendorCategory.addEventListener('change', function () {
    fetch(`/credits/${vendorCategory.value}`)
        .then(response => response.json())
        .then(data => {
            vendorCategoryValue.innerHTML = '';
            var number = 1;

            if (data.length === 0) {
                vendorCategoryValue.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data yang tersedia.</td></tr>';
            } else {
                data.forEach((item) => {
                    vendorCategoryValue.innerHTML += `
                    <tr>
                        <td class="text-center">${number}</td>
                        <td>Rp${formatRupiah(item.credit)}</td>
                        <td>
                            ${item.category === 'vendor_outcome' ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Cash out</span>' : item.category === 'customer_transaction_canceled' ? '<span class="badge rounded-pill text-warning-emphasis bg-warning-subtle border border-warning-subtle">Pengembalian dana</span>' : '<span class="badge rounded-pill text-success-emphasis bg-success-subtle border border-success-subtle">Penjualan</span>'}
                        </td>
                        <td>${moment(item.created_at).format('dddd, D MMMM YYYY H:mm:ss')}</td>
                    </tr>`;
                    number++;
                });
            }
        })
        .catch(error => console.error('Error:', error));
});

fetch(`/credits/${vendorCategory.value}`)
    .then(response => response.json())
    .then(data => {
        vendorCategoryValue.innerHTML = '';
        var number = 1;

        if (data.length === 0) {
            vendorCategoryValue.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data yang tersedia.</td></tr>';
        } else {
            data.forEach((item) => {
                vendorCategoryValue.innerHTML += `
                <tr>
                    <td class="text-center">${number}</td>
                    <td>Rp${formatRupiah(item.credit)}</td>
                    <td> ${item.category === 'vendor_outcome' ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Cash out</span>' : item.category === 'customer_transaction_canceled' ? '<span class="badge rounded-pill text-warning-emphasis bg-warning-subtle border border-warning-subtle">Pengembalian dana</span>' : '<span class="badge rounded-pill text-success-emphasis bg-success-subtle border border-success-subtle">Penjualan</span>'}
                    </td>
                    <td>${moment(item.created_at).format('dddd, D MMMM YYYY H:mm:ss')}</td>
                </tr>`;
                number++;
            });
        }
    })

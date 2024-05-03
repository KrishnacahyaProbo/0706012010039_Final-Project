// Mencegah input Nominal yang melebihi balanceNominal
document.addEventListener("DOMContentLoaded", function () {
    // Mendapatkan elemen input Nominal
    var creditInput = document.getElementById('credit_cash_out');

    // Mengatur nilai maksimum input Nominal berdasarkan nilai balanceNominal
    creditInput.max = balanceNominal;
});

var customerCategory = document.getElementById('customer_category')
var customerCategoryValue = document.getElementById('customer_category_value')

customerCategory.addEventListener('change', function () {
    fetch(`/credits/${customerCategory.value}`)
        .then(response => response.json())
        .then(data => {
            customerCategoryValue.innerHTML = '';
            var number = 1;

            if (data.length === 0) {
                customerCategoryValue.innerHTML = '<tr><td colspan="5">Belum ada riwayat.</td></tr>';
            } else {
                data.forEach((item) => {
                    customerCategoryValue.innerHTML += `
                    <tr>
                        <td class="text-center">${number}</td>
                        <td>Rp${formatRupiah(item.credit)}</td>
                        <td>
                            ${item.category === 'customer_outcome' ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pembelian</span>' : '<span class="badge rounded-pill text-success-emphasis bg-success-subtle border border-success-subtle">Top up</span>'}
                        </td>
                        <td>${moment(item.created_at).format('dddd, D MMMM YYYY H:mm:ss')}</td>
                        <td>
                            ${item.category === 'customer_outcome' ? '-' : `<a href="/assets/image/transaction_proof/${item.transaction_proof}" target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="bi bi-info-circle"></i></a>`}
                        </td>
                    </tr>`;
                    number++;
                });
            }
        })
        .catch(error => console.error('Error:', error));
});

fetch(`/credits/${customerCategory.value}`)
    .then(response => response.json())
    .then(data => {
        customerCategoryValue.innerHTML = '';
        var number = 1;

        if (data.length === 0) {
            customerCategoryValue.innerHTML = '<tr><td colspan="5">Belum ada riwayat.</td></tr>';
        } else {
            data.forEach((item) => {
                customerCategoryValue.innerHTML += `
                <tr>
                    <td class="text-center">${number}</td>
                    <td>Rp${formatRupiah(item.credit)}</td>
                    <td>${item.category === 'customer_outcome' ? '<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pembelian</span>' : '<span class="badge rounded-pill text-success-emphasis bg-success-subtle border border-success-subtle">Top up</span>'}
                    </td>
                    <td>${moment(item.created_at).format('dddd, D MMMM YYYY H:mm:ss')}</td>
                    <td>${item.category === 'customer_outcome' ? '-' : `<a href="/assets/image/transaction_proof/${item.transaction_proof}" target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="bi bi-info-circle"></i></a>`}
                    </td>
                </tr>`;
                number++;
            });
        }
    })

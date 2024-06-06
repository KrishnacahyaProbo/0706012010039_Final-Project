var customerCategory = document.getElementById('customer_category')
var customerCategoryValue = document.getElementById('customer_category_value')

customerCategory.addEventListener('change', function () {
    fetch(`/credits/${customerCategory.value}`)
        .then(response => response.json())
        .then(data => {
            customerCategoryValue.innerHTML = '';
            var number = 1;

            if (data.length === 0) {
                customerCategoryValue.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data yang tersedia.</td></tr>';
            } else {
                data.forEach((item) => {
                    customerCategoryValue.innerHTML += `
                    <tr>
                        <td class="text-center">${number}</td>
                        <td>Rp${formatRupiah(item.credit)}</td>
                        <td>
                        ${item.category === 'customer_income' ?
                            `<span class="badge rounded-pill text-success-emphasis bg-success-subtle border border-success-subtle">Top up</span>` :
                            item.category === 'customer_outcome' ?
                                `<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pembelian</span>` :
                                item.category === 'customer_transaction_refund' ?
                                    `<span class="badge rounded-pill text-warning-emphasis bg-warning-subtle border border-warning-subtle">Pengembalian dana</span>` :
                                    `<span class="badge rounded-pill text-warning-emphasis bg-warning-subtle border border-warning-subtle">Pembatalan pembelian</span>`}
                        </td>
                        <td>${moment(item.created_at).format('dddd, D MMMM YYYY HH:mm:ss')}</td>
                        <td>
                            ${item.category === 'customer_outcome' ||
                            item.category === 'customer_transaction_refund' ||
                            item.category === 'customer_transaction_canceled' ? '-' :
                            `<a href="/assets/image/transaction_proof/${item.transaction_proof}" target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="bi bi-info-circle"></i></a>`}
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
            customerCategoryValue.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data yang tersedia.</td></tr>';
        } else {
            data.forEach((item) => {
                customerCategoryValue.innerHTML += `
                <tr>
                    <td class="text-center">${number}</td>
                    <td>Rp${formatRupiah(item.credit)}</td>
                    <td>
                        ${item.category === 'customer_income' ?
                        `<span class="badge rounded-pill text-success-emphasis bg-success-subtle border border-success-subtle">Top up</span>` :
                        item.category === 'customer_outcome' ?
                            `<span class="badge rounded-pill text-danger-emphasis bg-danger-subtle border border-danger-subtle">Pembelian</span>` :
                            item.category === 'customer_transaction_refund' ?
                                `<span class="badge rounded-pill text-warning-emphasis bg-warning-subtle border border-warning-subtle">Pengembalian dana</span>` :
                                `<span class="badge rounded-pill text-warning-emphasis bg-warning-subtle border border-warning-subtle">Pembatalan pembelian</span>`}
                    </td>
                    <td>${moment(item.created_at).format('dddd, D MMMM YYYY HH:mm:ss')}</td>
                    <td>
                        ${item.category === 'customer_outcome' ||
                        item.category === 'customer_transaction_refund' ||
                        item.category === 'customer_transaction_canceled' ? '-' :
                        `<a href="/assets/image/transaction_proof/${item.transaction_proof}" target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="bi bi-info-circle"></i></a>`}
                        </td>
                </tr>`;
                number++;
            });
        }
    })

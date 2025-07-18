// Data sample - dalam implementasi nyata, ambil dari API/backend
const tagihanData = [{
        id: 1,
        jatuh_tempo: '2024-12-01',
        invoice: 'INV-2024-001',
        paket_wifi: 'Premium 100Mbps',
        jumlah: 165000,
        status_tagihan_id: 1,
        status_text: 'Belum Dibayar',
        pembayaran: null
    },
    {
        id: 2,
        jatuh_tempo: '2024-11-01',
        invoice: 'INV-2024-002',
        paket_wifi: 'Standard 50Mbps',
        jumlah: 125000,
        status_tagihan_id: 2,
        status_text: 'Sudah Dibayar',
        pembayaran: {
            tanggal_pembayaran: '2024-10-28',
            metode_pembayaran: 'Credit Card',
            status_pembayaran: 'success',
            midtrans_transaction_id: 'TXN-123456789'
        }
    },
    {
        id: 3,
        jatuh_tempo: '2024-12-15',
        invoice: 'INV-2024-003',
        paket_wifi: 'Basic 25Mbps',
        jumlah: 85000,
        status_tagihan_id: 3,
        status_text: 'Menunggu Konfirmasi',
        pembayaran: {
            tanggal_pembayaran: '2024-12-10',
            metode_pembayaran: 'Bank Transfer',
            status_pembayaran: 'pending',
            midtrans_transaction_id: 'TXN-987654321'
        }
    }
];

// Global variables
let currentTagihan = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadTagihanData();
    loadPaymentHistory();
    updateSummaryCards();
});

// Load tagihan data
function loadTagihanData() {
    const tbody = document.getElementById('tagihan-tbody');
    tbody.innerHTML = '';

    tagihanData.forEach(tagihan => {
        const row = createTagihanRow(tagihan);
        tbody.appendChild(row);
    });
}

// Create tagihan table row
function createTagihanRow(tagihan) {
    const tr = document.createElement('tr');

    // Format currency
    const formattedAmount = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(tagihan.jumlah);

    // Status badge
    let statusBadge = '';
    switch (tagihan.status_tagihan_id) {
        case 1:
            statusBadge = '<span class="badge badge-danger">Belum Dibayar</span>';
            break;
        case 2:
            statusBadge = '<span class="badge badge-success">Sudah Dibayar</span>';
            break;
        case 3:
            statusBadge = '<span class="badge badge-warning">Menunggu Konfirmasi</span>';
            break;
    }

    // Payment method
    let paymentMethod = '-';
    if (tagihan.pembayaran) {
        paymentMethod = tagihan.pembayaran.metode_pembayaran;
    }

    // Action button
    let actionButton = '';
    if (tagihan.status_tagihan_id === 1) {
        actionButton = `<button class="btn btn-success btn-sm" onclick="openPaymentModal(${tagihan.id})">
                            <i class="fas fa-credit-card"></i> Bayar
                        </button>`;
    } else if (tagihan.status_tagihan_id === 3) {
        actionButton = `<button class="btn btn-info btn-sm" onclick="checkPaymentStatus(${tagihan.id})">
                            <i class="fas fa-sync"></i> Cek Status
                        </button>`;
    } else {
        actionButton = `<button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-check"></i> Lunas
                        </button>`;
    }

    tr.innerHTML = `
        <td>${formatDate(tagihan.jatuh_tempo)}</td>
        <td><code>${tagihan.invoice}</code></td>
        <td>${tagihan.paket_wifi}</td>
        <td>${formattedAmount}</td>
        <td>${statusBadge}</td>
        <td>${paymentMethod}</td>
        <td>${actionButton}</td>
    `;

    return tr;
}

// Load payment history
function loadPaymentHistory() {
    const tbody = document.getElementById('payment-history-tbody');
    tbody.innerHTML = '';

    const paidTagihan = tagihanData.filter(t => t.pembayaran !== null);

    paidTagihan.forEach(tagihan => {
        const tr = document.createElement('tr');

        const formattedAmount = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(tagihan.jumlah);

        let statusBadge = '';
        if (tagihan.pembayaran.status_pembayaran === 'success') {
            statusBadge = '<span class="badge badge-success">Berhasil</span>';
        } else if (tagihan.pembayaran.status_pembayaran === 'pending') {
            statusBadge = '<span class="badge badge-warning">Pending</span>';
        }

        tr.innerHTML = `
            <td>${formatDate(tagihan.pembayaran.tanggal_pembayaran)}</td>
            <td><code>${tagihan.invoice}</code></td>
            <td>${formattedAmount}</td>
            <td>${tagihan.pembayaran.metode_pembayaran}</td>
            <td>${statusBadge}</td>
            <td><small><code>${tagihan.pembayaran.midtrans_transaction_id}</code></small></td>
        `;

        tbody.appendChild(tr);
    });
}

// Update summary cards
function updateSummaryCards() {
    const unpaid = tagihanData.filter(t => t.status_tagihan_id === 1).length;
    const paid = tagihanData.filter(t => t.status_tagihan_id === 2).length;
    const pending = tagihanData.filter(t => t.status_tagihan_id === 3).length;
    const total = tagihanData.reduce((sum, t) => sum + t.jumlah, 0);

    document.getElementById('unpaid-count').textContent = unpaid;
    document.getElementById('paid-count').textContent = paid;
    document.getElementById('pending-count').textContent = pending;
    document.getElementById('total-amount').textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(total);
}

// Open payment modal
function openPaymentModal(tagihanId) {
    currentTagihan = tagihanData.find(t => t.id === tagihanId);

    if (!currentTagihan) return;

    document.getElementById('modal-invoice').textContent = currentTagihan.invoice;
    document.getElementById('modal-paket').textContent = currentTagihan.paket_wifi;
    document.getElementById('modal-due-date').textContent = formatDate(currentTagihan.jatuh_tempo);
    document.getElementById('modal-amount').textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(currentTagihan.jumlah);
    document.getElementById('modal-status').innerHTML = '<span class="badge badge-danger">Belum Dibayar</span>';

    $('#paymentModal').modal('show');
}

// Confirm payment
document.getElementById('confirm-payment-btn').addEventListener('click', function() {
    if (!currentTagihan) return;

    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const btn = this;

    // Show loading
    btnText.textContent = 'Memproses...';
    btnSpinner.classList.remove('d-none');
    btn.disabled = true;

    // Create transaction
    fetch('/create-transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                tagihan_id: currentTagihan.id,
                amount: currentTagihan.jumlah,
                invoice: currentTagihan.invoice
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal membuat transaksi');
            }
            return response.json();
        })
        .then(data => {
            if (data.snapToken) {
                $('#paymentModal').modal('hide');

                // Call Midtrans Snap
                snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        handlePaymentSuccess(result);
                    },
                    onPending: function(result) {
                        handlePaymentPending(result);
                    },
                    onError: function(result) {
                        handlePaymentError(result);
                    },
                    onClose: function() {
                        showAlert('info', 'Pembayaran dibatalkan oleh pengguna');
                    }
                });
            } else {
                throw new Error('Snap Token tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Terjadi kesalahan saat memproses pembayaran: ' + error.message);
        })
        .finally(() => {
            // Reset button
            btnText.textContent = 'Bayar Sekarang';
            btnSpinner.classList.add('d-none');
            btn.disabled = false;
        });
});

// Handle payment success
function handlePaymentSuccess(result) {
    showAlert('success', 'Pembayaran berhasil! Terima kasih atas pembayaran Anda.');

    // Update tagihan status (simulate API call)
    if (currentTagihan) {
        currentTagihan.status_tagihan_id = 2;
        currentTagihan.status_text = 'Sudah Dibayar';
        currentTagihan.pembayaran = {
            tanggal_pembayaran: new Date().toISOString().split('T')[0],
            metode_pembayaran: result.payment_type || 'Credit Card',
            status_pembayaran: 'success',
            midtrans_transaction_id: result.transaction_id
        };

        // Reload data
        loadTagihanData();
        loadPaymentHistory();
        updateSummaryCards();
    }

    console.log('Payment Success:', result);
}

// Handle payment pending
function handlePaymentPending(result) {
    showAlert('warning', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.');

    // Update tagihan status (simulate API call)
    if (currentTagihan) {
        currentTagihan.status_tagihan_id = 3;
        currentTagihan.status_text = 'Menunggu Konfirmasi';
        currentTagihan.pembayaran = {
            tanggal_pembayaran: new Date().toISOString().split('T')[0],
            metode_pembayaran: result.payment_type || 'Bank Transfer',
            status_pembayaran: 'pending',
            midtrans_transaction_id: result.transaction_id
        };

        // Reload data
        loadTagihanData();
        loadPaymentHistory();
        updateSummaryCards();
    }

    console.log('Payment Pending:', result);
}

// Handle payment error
function handlePaymentError(result) {
    showAlert('danger', 'Pembayaran gagal. Silakan coba lagi atau hubungi customer service.');
    console.error('Payment Error:', result);
}

// Check payment status
function checkPaymentStatus(tagihanId) {
    const tagihan = tagihanData.find(t => t.id === tagihanId);
    if (!tagihan || !tagihan.pembayaran) return;

    // Simulate API call to check status
    fetch(`/check-payment-status/${tagihan.pembayaran.midtrans_transaction_id}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.transaction_status === 'settlement') {
                tagihan.status_tagihan_id = 2;
                tagihan.status_text = 'Sudah Dibayar';
                tagihan.pembayaran.status_pembayaran = 'success';

                showAlert('success', 'Status pembayaran berhasil diperbarui!');
                loadTagihanData();
                loadPaymentHistory();
                updateSummaryCards();
            } else {
                showAlert('info', `Status pembayaran: ${data.transaction_status}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Gagal mengecek status pembayaran');
        });
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    const alertClass = type === 'danger' ? 'alert-danger' :
        type === 'success' ? 'alert-success' :
        type === 'warning' ? 'alert-warning' : 'alert-info';

    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    alertContainer.appendChild(alert);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 5000);
}

function refreshData() {
    showAlert('info', 'Memuat ulang data...');
    // Simulate refresh
    setTimeout(() => {
        loadTagihanData();
        loadPaymentHistory();
        updateSummaryCards();
        showAlert('success', 'Data berhasil dimuat ulang');
    }, 1000)
}
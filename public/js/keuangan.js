// File: public/js/keuangan.js - Optimized Real Data Implementation

$(document).ready(function() {
    // Initialize components
    initializeDateRangePicker();
    initializeDataTables();

    // Load initial data
    loadFinancialData();

    // Event listeners
    $('#filterPaket, #filterStatus').on('change', function() {
        loadFinancialData();
    });

    $('#daterange').on('apply.daterangepicker', function() {
        loadFinancialData();
    });

    // Refresh button
    $('#refreshData').on('click', function() {
        loadFinancialData();
    });
});

// Initialize date range picker
function initializeDateRangePicker() {
    $('#daterange').daterangepicker({
        startDate: moment().startOf('year'),
        endDate: moment().endOf('year'),
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            fromLabel: 'Dari',
            toLabel: 'Sampai',
            customRangeLabel: 'Custom',
            weekLabel: 'W',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
            firstDay: 1
        },
        ranges: {
            'Hari ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
            '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
            'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
            'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
            'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
        }
    });
}

// Load financial data from server
function loadFinancialData() {
    showLoading();

    const filters = getFilters();

    $.ajax({
        url: '/financial/data', // Sesuaikan dengan route Laravel Anda
        method: 'GET',
        data: filters,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Update all components with real data
            updateSummaryCards(response.summary);
            updateCharts(response.charts);
            updateTransactionTable(response.transactions);
            updatePackageTable(response.packages);
            hideLoading();
        },
        error: function(xhr, status, error) {
            console.error('Error loading financial data:', error);
            let errorMessage = 'Gagal memuat data keuangan';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            showError(errorMessage);
            hideLoading();
        }
    });
}

// Get current filters
function getFilters() {
    const dateRange = $('#daterange').val().split(' - ');
    return {
        start_date: moment(dateRange[0], 'DD/MM/YYYY').format('YYYY-MM-DD'),
        end_date: moment(dateRange[1], 'DD/MM/YYYY').format('YYYY-MM-DD'),
        paket: $('#filterPaket').val() || '',
        status: $('#filterStatus').val() || ''
    };
}

// Update summary cards with real data
function updateSummaryCards(data) {
    // Update values without animation first
    $('#totalPendapatan').text(formatCurrency(data.total_revenue || 0));
    $('#pembayaranLunas').text(formatCurrency(data.paid_amount || 0));
    $('#pembayaranPending').text(formatCurrency(data.pending_amount || 0));
    $('#belumDiBayar').text(formatCurrency(data.unpaid_amount || 0));

    // Add subtle animation
    animateCounter('#totalPendapatan', data.total_revenue || 0);
    animateCounter('#pembayaranLunas', data.paid_amount || 0);
    animateCounter('#pembayaranPending', data.pending_amount || 0);
    animateCounter('#belumDiBayar', data.unpaid_amount || 0);

    // Update percentage indicators if exists
    updatePercentageIndicators(data);
}

// Update percentage indicators
function updatePercentageIndicators(data) {
    const total = data.total_revenue || 0;
    if (total > 0) {
        const paidPercent = ((data.paid_amount || 0) / total * 100).toFixed(1);
        const pendingPercent = ((data.pending_amount || 0) / total * 100).toFixed(1);
        const unpaidPercent = ((data.unpaid_amount || 0) / total * 100).toFixed(1);

        $('#paidPercentage').text(paidPercent + '%');
        $('#pendingPercentage').text(pendingPercent + '%');
        $('#unpaidPercentage').text(unpaidPercent + '%');
    }
}

// Update charts with real data
function updateCharts(data) {
    updatePendapatanChart(data.monthly_revenue);
    updateStatusChart(data.status_distribution);
}

// Update monthly revenue chart
function updatePendapatanChart(data) {
    const ctx = document.getElementById('pendapatanChart');
    if (!ctx) return;

    if (window.pendapatanChart) {
        window.pendapatanChart.destroy();
    }

    window.pendapatanChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Pendapatan Bulanan',
                data: data.amounts || [],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: ' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            }
        }
    });
}

// Update status distribution chart
function updateStatusChart(data) {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    if (window.statusChart) {
        window.statusChart.destroy();
    }

    const chartData = [
        data.paid || 0,
        data.pending || 0,
        data.unpaid || 0
    ];

    window.statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Pending', 'Belum Bayar'],
            datasets: [{
                data: chartData,
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = formatCurrency(context.parsed);
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Update transaction table with real data
function updateTransactionTable(data) {
    const tableBody = $('#transaksiTableBody');
    tableBody.empty();

    if (!data || data.length === 0) {
        tableBody.append('<tr><td colspan="8" class="text-center">Tidak ada data transaksi</td></tr>');
        return;
    }

    data.forEach(function(transaction, index) {
                const statusBadge = getStatusBadge(transaction.status);
                const metodeBayar = transaction.payment_method || 'Belum Bayar';
                const formattedDate = formatDate(transaction.created_at);

                const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${formattedDate}</td>
                <td>${escapeHtml(transaction.customer_name)}</td>
                <td>${escapeHtml(transaction.package_name)}</td>
                <td class="text-right">${formatCurrency(transaction.amount)}</td>
                <td>${statusBadge}</td>
                <td>${escapeHtml(metodeBayar)}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-info" onclick="viewTransaction(${transaction.id})" 
                                title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${transaction.status !== 'success' ? `
                            <button class="btn btn-success" onclick="markAsPaid(${transaction.id})"
                                    title="Tandai Lunas">
                                <i class="fas fa-check"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
        tableBody.append(row);
    });
    
    // Refresh DataTable
    refreshDataTable();
}

// Update package performance table
function updatePackageTable(data) {
    const tableBody = $('#paketTableBody');
    tableBody.empty();
    
    if (!data || data.length === 0) {
        tableBody.append('<tr><td colspan="5" class="text-center">Tidak ada data paket</td></tr>');
        return;
    }
    
    const totalRevenue = data.reduce((sum, pkg) => sum + (pkg.total_revenue || 0), 0);
    
    data.forEach(function(pkg) {
        const revenue = pkg.total_revenue || 0;
        const percentage = totalRevenue > 0 ? ((revenue / totalRevenue) * 100).toFixed(1) : 0;
        const avgPerCustomer = pkg.customer_count > 0 ? (revenue / pkg.customer_count) : 0;
        
        const row = `
            <tr>
                <td>${escapeHtml(pkg.package_name)}</td>
                <td class="text-center">${pkg.customer_count || 0}</td>
                <td class="text-right">${formatCurrency(revenue)}</td>
                <td class="text-right">${formatCurrency(avgPerCustomer)}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="mr-2 text-muted">${percentage}%</span>
                        <div class="progress flex-grow-1" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: ${percentage}%"></div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        tableBody.append(row);
    });
}

// Initialize DataTables
function initializeDataTables() {
    // Initialize with empty state first
    if ($.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().destroy();
    }
}

// Refresh DataTable
function refreshDataTable() {
    if ($.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().destroy();
    }
    
    $('#dataTable').DataTable({
        pageLength: 25,
        order: [[1, 'desc']],
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json'
        },
        columnDefs: [
            { targets: [4], className: 'text-right' },
            { targets: [7], orderable: false }
        ]
    });
}

// Export functions
function exportToExcel() {
    const filters = getFilters();
    showLoading();
    
    $.ajax({
        url: '/financial/export/excel',
        method: 'GET',
        data: filters,
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data) {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `laporan-keuangan-${moment().format('YYYY-MM-DD')}.xlsx`;
            a.click();
            window.URL.revokeObjectURL(url);
            hideLoading();
        },
        error: function() {
            showError('Gagal mengekspor data ke Excel');
            hideLoading();
        }
    });
}

function exportToPDF() {
    const filters = getFilters();
    showLoading();
    
    $.ajax({
        url: '/financial/export/pdf',
        method: 'GET',
        data: filters,
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data) {
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `laporan-keuangan-${moment().format('YYYY-MM-DD')}.pdf`;
            a.click();
            window.URL.revokeObjectURL(url);
            hideLoading();
        },
        error: function() {
            showError('Gagal mengekspor data ke PDF');
            hideLoading();
        }
    });
}

// View transaction details
function viewTransaction(id) {
    $.ajax({
        url: `/financial/transaction/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showTransactionModal(response.transaction);
        },
        error: function(xhr) {
            let errorMessage = 'Gagal memuat detail transaksi';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showError(errorMessage);
        }
    });
}

// Mark transaction as paid
function markAsPaid(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menandai transaksi ini sebagai lunas?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tandai Lunas',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/financial/transaction/${id}/mark-paid`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess('Transaksi berhasil ditandai sebagai lunas');
                        loadFinancialData();
                    } else {
                        showError(response.message || 'Gagal mengupdate status transaksi');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Gagal mengupdate status transaksi';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showError(errorMessage);
                }
            });
        }
    });
}

// Show transaction modal
function showTransactionModal(transaction) {
    const modalContent = `
        <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi #${transaction.order_id}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr><td><strong>Order ID:</strong></td><td>${transaction.order_id}</td></tr>
                                    <tr><td><strong>Pelanggan:</strong></td><td>${escapeHtml(transaction.customer_name)}</td></tr>
                                    <tr><td><strong>Paket:</strong></td><td>${escapeHtml(transaction.package_name)}</td></tr>
                                    <tr><td><strong>Jumlah:</strong></td><td class="text-success font-weight-bold">${formatCurrency(transaction.amount)}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr><td><strong>Status:</strong></td><td>${getStatusBadge(transaction.status)}</td></tr>
                                    <tr><td><strong>Metode Bayar:</strong></td><td>${escapeHtml(transaction.payment_method || 'Belum Bayar')}</td></tr>
                                    <tr><td><strong>Tanggal:</strong></td><td>${formatDate(transaction.created_at)}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        ${transaction.status !== 'success' ? `
                            <button type="button" class="btn btn-success" onclick="markAsPaid(${transaction.id}); $('#transactionModal').modal('hide');">
                                Tandai Lunas
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#transactionModal').remove();
    $('body').append(modalContent);
    $('#transactionModal').modal('show');
}

// Utility functions
function formatCurrency(amount) {
    if (amount === null || amount === undefined) amount = 0;
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    return moment(dateString).format('DD/MM/YYYY HH:mm');
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/&/g, '&amp;')
               .replace(/</g, '&lt;')
               .replace(/>/g, '&gt;')
               .replace(/"/g, '&quot;')
               .replace(/'/g, '&#039;');
}

function getStatusBadge(status) {
    const badges = {
        'success': '<span class="badge badge-success">Lunas</span>',
        'settlement': '<span class="badge badge-success">Lunas</span>',
        'capture': '<span class="badge badge-success">Lunas</span>',
        'pending': '<span class="badge badge-warning">Pending</span>',
        'challenge': '<span class="badge badge-warning">Challenge</span>',
        'deny': '<span class="badge badge-danger">Ditolak</span>',
        'cancel': '<span class="badge badge-danger">Dibatalkan</span>',
        'expire': '<span class="badge badge-danger">Kedaluwarsa</span>',
        'failure': '<span class="badge badge-danger">Gagal</span>',
        'error': '<span class="badge badge-danger">Error</span>'
    };
    
    return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
}

function animateCounter(selector, targetValue) {
    const element = $(selector);
    const startValue = 0;
    const duration = 1500;
    
    $({countNum: startValue}).animate({countNum: targetValue}, {
        duration: duration,
        easing: 'easeOutQuart',
        step: function() {
            element.text(formatCurrency(Math.floor(this.countNum)));
        },
        complete: function() {
            element.text(formatCurrency(targetValue));
        }
    });
}

function showLoading() {
    if ($('#loadingOverlay').length === 0) {
        $('body').append(`
            <div id="loadingOverlay" class="loading-overlay">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">Memuat data...</div>
                </div>
            </div>
        `);
    }
    $('#loadingOverlay').show();
}

function hideLoading() {
    $('#loadingOverlay').hide();
}

function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else if (typeof toastr !== 'undefined') {
        toastr.success(message);
    } else {
        alert(message);
    }
}

function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    } else if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else {
        alert(message);
    }
}

function showInfo(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: message
        });
    } else if (typeof toastr !== 'undefined') {
        toastr.info(message);
    } else {
        alert(message);
    }
}
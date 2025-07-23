// export-tagihan.js
// Fungsi untuk export tagihan ke Excel dan PDF

// Loading overlay menggunakan Bootstrap classes
function showLoadingOverlay(message = 'Memproses...') {
    const overlay = document.createElement('div');
    overlay.id = 'exportLoadingOverlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;

    overlay.innerHTML = `
        <div class="bg-white p-4 rounded text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mb-0">${message}</p>
        </div>
    `;
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('exportLoadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

// Export to Excel function
async function exportToExcel() {
    try {
        showLoadingOverlay('Menyiapkan file Excel...');

        const response = await fetch('/tagihan/export/excel', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? .getAttribute('content') || ''
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Get filename from header or use default
        const contentDisposition = response.headers.get('Content-Disposition');
        let filename = `tagihan_${new Date().toISOString().split('T')[0]}.xlsx`;

        if (contentDisposition) {
            const filenameMatch = contentDisposition.match(/filename="(.+)"/);
            if (filenameMatch) {
                filename = filenameMatch[1];
            }
        }

        // Create blob and download
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        hideLoadingOverlay();
        showAlert('success', 'Export Excel berhasil! File telah diunduh.');

    } catch (error) {
        console.error('Export Excel error:', error);
        hideLoadingOverlay();
        showAlert('danger', 'Gagal export Excel: ' + error.message);
    }
}

// Export to PDF function
async function exportToPDF() {
    try {
        showLoadingOverlay('Menyiapkan file PDF...');

        const response = await fetch('/tagihan/export/pdf', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? .getAttribute('content') || ''
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Get filename from header or use default
        const contentDisposition = response.headers.get('Content-Disposition');
        let filename = `tagihan_${new Date().toISOString().split('T')[0]}.pdf`;

        if (contentDisposition) {
            const filenameMatch = contentDisposition.match(/filename="(.+)"/);
            if (filenameMatch) {
                filename = filenameMatch[1];
            }
        }

        // Create blob and download
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        hideLoadingOverlay();
        showAlert('success', 'Export PDF berhasil! File telah diunduh.');

    } catch (error) {
        console.error('Export PDF error:', error);
        hideLoadingOverlay();
        showAlert('danger', 'Gagal export PDF: ' + error.message);
    }
}

// Show alert using Bootstrap classes
function showAlert(type, message) {
    // Remove existing alerts first
    const existingAlerts = document.querySelectorAll('.export-alert');
    existingAlerts.forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show export-alert`;
    alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';

    alertDiv.innerHTML = `
        <i class="${icon}"></i> ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    document.body.appendChild(alertDiv);

    // Auto hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Initialize export functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Export Tagihan JS loaded');

    // Add click event listeners to export dropdown items
    const exportExcelLink = document.querySelector('a[href*="tagihan.export.excel"]');
    const exportPDFLink = document.querySelector('a[href*="tagihan.export.pdf"]');

    if (exportExcelLink) {
        exportExcelLink.addEventListener('click', function(e) {
            e.preventDefault();
            exportToExcel();
        });
        console.log('Excel export listener attached');
    } else {
        console.log('Excel export link not found');
    }

    if (exportPDFLink) {
        exportPDFLink.addEventListener('click', function(e) {
            e.preventDefault();
            exportToPDF();
        });
        console.log('PDF export listener attached');
    } else {
        console.log('PDF export link not found');
    }
});

// Optional: Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+Shift+E for Excel export
    if (e.ctrlKey && e.shiftKey && e.key === 'E') {
        e.preventDefault();
        exportToExcel();
    }
    // Ctrl+Shift+P for PDF export  
    if (e.ctrlKey && e.shiftKey && e.key === 'P') {
        e.preventDefault();
        exportToPDF();
    }
});

// Function untuk generate tagihan bulanan (jika diperlukan)
function generateMonthlyBills() {
    if (confirm('Generate tagihan bulanan untuk semua pelanggan aktif?')) {
        showLoadingOverlay('Generating tagihan bulanan...');

        fetch('/tagihan/generate-monthly', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? .getAttribute('content') || '',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoadingOverlay();
                if (data.success) {
                    showAlert('success', 'Tagihan bulanan berhasil digenerate!');
                    // Reload halaman setelah 2 detik
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showAlert('danger', 'Gagal generate tagihan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                hideLoadingOverlay();
                showAlert('danger', 'Error: ' + error.message);
            });
    }
}
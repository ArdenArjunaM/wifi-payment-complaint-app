    $(document).ready(function() {
                // Inisialisasi sistem notifikasi
                initializeNotificationSystem();

                function initializeNotificationSystem() {
                    // Event listeners
                    setupEventListeners();

                    // Load initial notifications
                    loadNotifications();

                    // Setup auto refresh
                    setupAutoRefresh();

                    // Setup keyboard shortcuts
                    setupKeyboardShortcuts();
                }

                function setupEventListeners() {
                    // Memuat notifikasi saat dropdown dibuka
                    $('#alertsDropdown').on('click', function() {
                        loadNotifications();
                    });

                    // Event handler untuk menandai notifikasi sebagai dibaca
                    $(document).on('click', '.notification-item', function() {
                        const notificationId = $(this).data('id');
                        const isRead = $(this).data('read');

                        if (!isRead) {
                            markAsRead(notificationId, $(this));
                        }
                    });

                    // Event handler untuk tombol detail
                    $(document).on('click', '.view-detail-btn', function(e) {
                        e.stopPropagation();
                        const notificationId = $(this).data('id');
                        viewNotificationDetail(notificationId);
                    });

                    // Event handler untuk tombol hapus individual
                    $(document).on('click', '.delete-notification-btn', function(e) {
                        e.stopPropagation();
                        const notificationId = $(this).data('id');

                        if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                            deleteNotification(notificationId);
                        }
                    });

                    // Event handler untuk mark all as read
                    $('#mark-all-read').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent dropdown close
                        markAllAsRead();
                    });

                    // Event handler untuk delete all notifications
                    $('#delete-all-notifications').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent dropdown close

                        // Improved confirmation dialog
                        const confirmDialog = confirm('Apakah Anda yakin ingin menghapus SEMUA notifikasi?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua notifikasi baik yang sudah dibaca maupun yang belum.');

                        if (confirmDialog) {
                            deleteAllNotifications();
                        }
                    });

                    // Prevent dropdown close ketika mengklik di dalam area notifikasi
                    $(document).on('click', '.notification-wrapper', function(e) {
                        e.stopPropagation();
                    });

                    // Prevent dropdown close ketika mengklik di area container
                    $(document).on('click', '.dropdown-list', function(e) {
                        e.stopPropagation();
                    });
                }

                // Memuat notifikasi melalui AJAX
                function loadNotifications() {
                    $('#notification-loading').show();
                    $('#notifications-container').empty();
                    $('#no-notifications').hide();

                    $.ajax({
                        url: '/notifications',
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            $('#notification-loading').hide();

                            if (response.notifications && response.notifications.length === 0) {
                                $('#no-notifications').show();
                                $('#notifications-container').empty();
                                updateNotificationCounter(0);
                            } else if (response.notifications) {
                                $('#no-notifications').hide();
                                displayNotifications(response.notifications);
                                updateNotificationCounter(response.unread_count || 0);
                            } else {
                                // Handle unexpected response format
                                $('#no-notifications').show();
                                console.warn('Unexpected response format:', response);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#notification-loading').hide();
                            console.error('Gagal memuat notifikasi:', error);
                            console.error('Response:', xhr.responseText);
                            showToast('Gagal memuat notifikasi. Silakan refresh halaman.', 'error');

                            // Show error state
                            $('#notifications-container').html(`
                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-triangle text-warning mb-2"></i>
                            <p class="text-muted small mb-0">Gagal memuat notifikasi</p>
                            <button onclick="loadNotifications()" class="btn btn-sm btn-link">Coba Lagi</button>
                        </div>
                    `);
                        }
                    });
                }

                // Menampilkan notifikasi di dropdown
                function displayNotifications(notifications) {
                    let notificationHtml = '';

                    notifications.forEach(function(notification) {
                        const createdAt = notification.created_at_human || notification.created_at || 'Tidak diketahui';
                        const title = notification.title || 'Notifikasi';
                        const message = notification.message || 'Tidak ada pesan';
                        const isRead = notification.is_read || false;

                        notificationHtml += `
                    <div class="dropdown-item notification-wrapper ${isRead ? '' : 'unread'}" data-id="${notification.id}">
                        <div class="notification-item d-flex align-items-center" data-id="${notification.id}" data-read="${isRead}">
                            <div class="mr-3">
                                <div class="icon-circle ${getBadgeClass(notification.type).replace('badge-', 'bg-')}">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">${createdAt}</div>
                                <div class="font-weight-bold">${title}</div>
                                <div class="small text-gray-600">${truncateText(message, 50)}</div>
                            </div>
                        </div>
                        <div class="notification-actions d-flex mt-2">
                            <button class="btn btn-sm btn-outline-primary mr-2 view-detail-btn" data-id="${notification.id}" title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-notification-btn" data-id="${notification.id}" title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        <hr class="dropdown-divider">
                    </div>
                `;
                    });

                    $('#notifications-container').html(notificationHtml);
                }

                // Memperbarui jumlah notifikasi yang belum dibaca
                function updateNotificationCounter(unreadCount) {
                    const counter = $('#notification-counter');
                    if (unreadCount > 0) {
                        counter.text(unreadCount).show();
                    } else {
                        counter.hide();
                    }
                }

                // Menandai notifikasi sebagai dibaca melalui AJAX
                function markAsRead(notificationId, element) {
                    $.ajax({
                        url: `/notifications/${notificationId}/read`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            // Menandai elemen sebagai dibaca dengan menghapus kelas 'unread'
                            const notificationWrapper = element.closest('.notification-wrapper');
                            if (notificationWrapper.hasClass('unread')) {
                                notificationWrapper.removeClass('unread');
                            }

                            // Memperbarui data read pada elemen
                            element.data('read', true);

                            // Update hanya counter notifikasi tanpa perlu memuat ulang semua
                            updateNotificationCounter(response.unread_count);

                            // Menampilkan notifikasi toast berhasil
                            showToast('Notifikasi telah ditandai sebagai dibaca', 'success');
                        },
                        error: function(xhr, status, error) {
                            console.error('Gagal menandai notifikasi sebagai dibaca:', error);
                            showToast('Gagal menandai notifikasi sebagai dibaca', 'error');
                        }
                    });
                }

                // Fungsi untuk memperbarui jumlah notifikasi yang belum dibaca
                function updateNotificationCounter(unreadCount) {
                    const counter = $('#notification-counter');
                    if (unreadCount > 0) {
                        counter.text(unreadCount).show();
                    } else {
                        counter.hide();
                    }
                }



                // Fungsi untuk melihat detail notifikasi
                function viewNotificationDetail(notificationId) {
                    console.log('Viewing notification detail for ID:', notificationId);

                    // Pastikan modal ada di DOM
                    if ($('#notificationDetailModal').length === 0) {
                        createDetailModal();
                    }

                    // Tampilkan loading di modal
                    $('#detailModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat detail notifikasi...</p>
                </div>
            `);

                    $('#notificationDetailModal').modal('show');

                    $.ajax({
                        url: `/notifications/${notificationId}/detail`,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        success: function(response) {
                            console.log('Success response:', response);

                            if (response.notification) {
                                const notification = response.notification;
                                const detailHtml = buildDetailHtml(notification);
                                $('#detailModalBody').html(detailHtml);

                                // Auto mark as read jika belum dibaca
                                if (!notification.is_read) {
                                    markAsRead(notificationId, $(`.notification-item[data-id="${notificationId}"]`));
                                }
                            } else {
                                $('#detailModalBody').html(`
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Data notifikasi tidak ditemukan atau sudah dihapus.
                            </div>
                        `);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error Details:');
                            console.error('Status Code:', xhr.status);
                            console.error('Status Text:', xhr.statusText);
                            console.error('Response Text:', xhr.responseText);

                            const errorMessage = getErrorMessage(xhr);

                            $('#detailModalBody').html(`
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Error ${xhr.status}</h6>
                            <p class="mb-2">${errorMessage}</p>
                            <small class="text-muted">
                                Status: ${xhr.status} ${xhr.statusText}<br>
                                ${xhr.responseText ? 'Response: ' + xhr.responseText.substring(0, 100) + (xhr.responseText.length > 100 ? '...' : '') : ''}
                            </small>
                            <div class="mt-3">
                                <button onclick="viewNotificationDetail(${notificationId})" class="btn btn-sm btn-primary">
                                    <i class="fas fa-redo mr-1"></i> Coba Lagi
                                </button>
                            </div>
                        </div>
                    `);
                        }
                    });
                }

                // Create modal jika belum ada
                function createDetailModal() {
                    const modalHtml = `
                <div class="modal fade" id="notificationDetailModal" tabindex="-1" role="dialog" aria-labelledby="notificationDetailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="notificationDetailModalLabel">
                                    <i class="fas fa-bell mr-2"></i>Detail Notifikasi
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="detailModalBody">
                                <!-- Content will be loaded here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i> Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    $('body').append(modalHtml);
                }

                // Build HTML untuk detail notifikasi
                function buildDetailHtml(notification) {
                    return `
                <div class="notification-detail">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted"><i class="fas fa-calendar mr-1"></i>Tanggal</h6>
                                <p class="mb-1">${notification.created_at || 'Tidak diketahui'}</p>
                                <small class="text-muted">${notification.created_at_human || ''}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted"><i class="fas fa-tag mr-1"></i>Tipe</h6>
                                <span class="badge ${getBadgeClass(notification.type)}">${notification.type || 'General'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="fas fa-heading mr-1"></i>Judul</h6>
                        <p class="font-weight-bold">${notification.title || 'Tidak ada judul'}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="fas fa-comment mr-1"></i>Pesan</h6>
                        <div class="p-3 bg-light rounded">
                            ${notification.message || 'Tidak ada pesan'}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="fas fa-eye mr-1"></i>Status</h6>
                        <span class="badge ${notification.is_read ? 'badge-secondary' : 'badge-primary'}">
                            <i class="fas ${notification.is_read ? 'fa-check' : 'fa-clock'} mr-1"></i>
                            ${notification.is_read ? 'Sudah Dibaca' : 'Belum Dibaca'}
                        </span>
                    </div>
                    
                    ${buildAdditionalDataHtml(notification)}
                    
                    ${notification.url ? `
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="fas fa-link mr-1"></i>Link Terkait</h6>
                        <a href="${notification.url}" class="btn btn-sm btn-primary" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka Link
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        // Build HTML untuk data tambahan (pengaduan atau data lainnya)
        function buildAdditionalDataHtml(notification) {
            let additionalHtml = '';
            
            // Periksa apakah ada data tambahan
            if (notification.data && typeof notification.data === 'object') {
                
                // Jika ada data pengaduan
                if (notification.data.pengaduan) {
                    additionalHtml += buildPengaduanHtml(notification.data.pengaduan);
                }
                
                // Jika ada pengaduan_id, tampilkan ID
                if (notification.data.pengaduan_id) {
                    additionalHtml += `
                        <div class="mb-3">
                            <h6 class="text-muted"><i class="fas fa-hashtag mr-1"></i>ID Pengaduan</h6>
                            <p class="font-weight-bold">#${notification.data.pengaduan_id}</p>
                        </div>
                    `;
                }
                
                // Jika ada data user
                if (notification.data.user) {
                    additionalHtml += `
                        <div class="mb-3">
                            <h6 class="text-muted"><i class="fas fa-user mr-1"></i>Data User</h6>
                            <div class="card">
                                <div class="card-body py-2">
                                    ${notification.data.user.name ? `<p class="mb-1"><strong>Nama:</strong> ${notification.data.user.name}</p>` : ''}
                                    ${notification.data.user.email ? `<p class="mb-0"><strong>Email:</strong> ${notification.data.user.email}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                // Jika ada data tambahan lainnya
                const excludeKeys = ['pengaduan', 'pengaduan_id', 'user'];
                const additionalData = Object.keys(notification.data)
                    .filter(key => !excludeKeys.includes(key))
                    .reduce((obj, key) => {
                        obj[key] = notification.data[key];
                        return obj;
                    }, {});
                
                if (Object.keys(additionalData).length > 0) {
                    additionalHtml += `
                        <div class="mb-3">
                            <h6 class="text-muted"><i class="fas fa-info-circle mr-1"></i>Data Tambahan</h6>
                            <div class="card">
                                <div class="card-body py-2">
                                    ${Object.entries(additionalData).map(([key, value]) => 
                                        `<p class="mb-1"><strong>${capitalizeFirst(key)}:</strong> ${formatValue(value)}</p>`
                                    ).join('')}
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            // Jika ada relasi pengaduan langsung dari model
            if (notification.pengaduan && !notification.data?.pengaduan) {
                additionalHtml += buildPengaduanHtml(notification.pengaduan);
            }
            
            return additionalHtml;
        }

        // Build HTML untuk data pengaduan
        function buildPengaduanHtml(pengaduan) {
            if (!pengaduan || typeof pengaduan !== 'object') {
                return '';
            }
            
            return `
                <div class="mb-3">
                    <h6 class="text-muted"><i class="fas fa-file-alt mr-1"></i>Data Pengaduan</h6>
                    <div class="card">
                        <div class="card-body">
                            ${pengaduan.id ? `
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>ID Pengaduan:</strong></div>
                                <div class="col-sm-8">#${pengaduan.id}</div>
                            </div>
                            ` : ''}
                            ${pengaduan.keluhan ? `
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Jenis Keluhan:</strong></div>
                                <div class="col-sm-8">${pengaduan.keluhan}</div>
                            </div>
                            ` : ''}
                            ${pengaduan.deskripsi_keluhan ? `
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Deskripsi:</strong></div>
                                <div class="col-sm-8">${pengaduan.deskripsi_keluhan}</div>
                            </div>
                            ` : ''}
                            ${pengaduan.status ? `
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Status:</strong></div>
                                <div class="col-sm-8">
                                    <span class="badge ${getStatusBadgeClass(pengaduan.status)}">
                                        ${pengaduan.status}
                                    </span>
                                </div>
                            </div>
                            ` : ''}
                            ${pengaduan.nama_pelapor ? `
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Pelapor:</strong></div>
                                <div class="col-sm-8">${pengaduan.nama_pelapor}</div>
                            </div>
                            ` : ''}
                            ${pengaduan.tanggal_pengaduan ? `
                            <div class="row mb-0">
                                <div class="col-sm-4"><strong>Tanggal:</strong></div>
                                <div class="col-sm-8">${formatDate(pengaduan.tanggal_pengaduan)}</div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        // Helper function untuk capitalize first letter
        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
        }

        // Helper function untuk format value
        function formatValue(value) {
            if (value === null || value === undefined) {
                return 'Tidak ada data';
            }
            if (typeof value === 'object') {
                return JSON.stringify(value, null, 2);
            }
            return String(value);
        }

        // Helper function untuk format date
        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return dateString;
            }
        }

        // Get error message berdasarkan response
        function getErrorMessage(xhr) {
            let errorMessage = 'Terjadi kesalahan saat memuat detail notifikasi.';
            
            switch(xhr.status) {
                case 404:
                    errorMessage = 'Notifikasi tidak ditemukan atau sudah dihapus.';
                    break;
                case 403:
                    errorMessage = 'Anda tidak memiliki akses ke notifikasi ini.';
                    break;
                case 500:
                    errorMessage = 'Terjadi kesalahan server internal. Silakan coba lagi atau hubungi administrator.';
                    break;
                case 422:
                    errorMessage = 'Data request tidak valid.';
                    break;
                case 0:
                    errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                    break;
            }
            
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.error) {
                    errorMessage = response.error;
                } else if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
                console.error('Could not parse error response:', e);
            }
            
            return errorMessage;
        }

        // Fungsi untuk menghapus notifikasi individual
        function deleteNotification(notificationId) {
            // Show loading state
            const notificationElement = $(`.notification-wrapper[data-id="${notificationId}"]`);
            notificationElement.addClass('opacity-50');

            $.ajax({
                url: `/notifications/${notificationId}/delete`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Delete success response:', response);
                    
                    notificationElement.fadeOut(300, function() {
                        $(this).remove();
                        // Reload notifications to update counter and check if empty
                        loadNotifications();
                    });
                    
                    showToast('Notifikasi berhasil dihapus', 'success');
                },
                error: function(xhr, status, error) {
                    console.error('Gagal menghapus notifikasi:', error);
                    console.error('Response:', xhr.responseText);
                    
                    // Remove loading state
                    notificationElement.removeClass('opacity-50');
                    
                    let errorMsg = 'Gagal menghapus notifikasi';
                    if (xhr.status === 404) {
                        errorMsg = 'Notifikasi tidak ditemukan atau sudah dihapus';
                    } else if (xhr.status === 403) {
                        errorMsg = 'Anda tidak memiliki akses untuk menghapus notifikasi ini';
                    }
                    
                    showToast(errorMsg, 'error');
                }
            });
        }

        // Fungsi untuk menandai semua notifikasi sebagai dibaca
        function markAllAsRead() {
            // Show loading state
            $('#mark-all-read').html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            $.ajax({
                url: '/notifications/mark-all-read',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Mark all read success:', response);
                    loadNotifications();
                    showToast('Semua notifikasi telah ditandai sebagai dibaca', 'success');
                },
                error: function(xhr, status, error) {
                    console.error('Gagal menandai semua notifikasi sebagai dibaca:', error);
                    console.error('Response:', xhr.responseText);
                    showToast('Gagal menandai semua notifikasi sebagai dibaca', 'error');
                },
                complete: function() {
                    // Reset button state
                    $('#mark-all-read').html('<i class="fas fa-check-double"></i>').prop('disabled', false);
                }
            });
        }

        // Fungsi untuk menghapus semua notifikasi - DIPERBAIKI
        function deleteAllNotifications() {
        // Show loading state
        $('#delete-all-notifications').html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
        
        // Show loading in container
        $('#notifications-container').html(`
            <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-danger" role="status">
                    <span class="sr-only">Menghapus...</span>
                </div>
                <p class="mt-2 text-muted small">Menghapus semua notifikasi...</p>
            </div>
        `);

        $.ajax({
            url: '/notifications/delete-all',
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Delete all success response:', response);
                
                // Clear notifications container and show empty state
                $('#notifications-container').empty();
                $('#no-notifications').show();
                updateNotificationCounter(0);
                
                showToast('Semua notifikasi berhasil dihapus', 'success');
            },
            error: function(xhr, status, error) {
                console.error('Gagal menghapus semua notifikasi:', error);
                console.error('Response Status:', xhr.status);
                console.error('Response Text:', xhr.responseText);
                
                let errorMsg = 'Gagal menghapus semua notifikasi';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        errorMsg = response.error;
                    } else if (response.message) {
                        errorMsg = response.message;
                    }
                } catch (e) {
                    if (xhr.status === 404) {
                        errorMsg = 'Endpoint tidak ditemukan. Periksa routing aplikasi.';
                    } else if (xhr.status === 403) {
                        errorMsg = 'Anda tidak memiliki akses untuk menghapus semua notifikasi';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Terjadi kesalahan server. Silakan coba lagi atau hubungi administrator.';
                    }
                }
                
                showToast(errorMsg, 'error');
                
                // Reload notifications to restore previous state
                loadNotifications();
            },
            complete: function() {
                // Reset button state
                $('#delete-all-notifications').html('<i class="fas fa-trash"></i>').prop('disabled', false);
            }
        });
    }


        // Helper function untuk badge class berdasarkan tipe
        function getBadgeClass(type) {
            const badgeClasses = {
                'pengaduan_baru': 'badge-danger',
                'pengaduan_diproses': 'badge-warning',
                'pengaduan_selesai': 'badge-success',
                'info': 'badge-info',
                'warning': 'badge-warning',
                'success': 'badge-success',
                'danger': 'badge-danger'
            };
            return badgeClasses[type] || 'badge-secondary';
        }

        // Helper function untuk status badge
        function getStatusBadgeClass(status) {
            const statusClasses = {
                'pending': 'badge-warning',
                'diproses': 'badge-info',
                'selesai': 'badge-success',
                'ditolak': 'badge-danger'
            };
            return statusClasses[status] || 'badge-secondary';
        }

        // Truncate text untuk tampilan ringkas
        function truncateText(text, maxLength) {
            if (!text || text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }

        // Setup auto refresh
        function setupAutoRefresh() {
            setInterval(function() {
                // Only update counter if dropdown is closed
                if (!$('#alertsDropdown').hasClass('show')) {
                    updateNotificationCounterOnly();
                }
            }, 30000); // Every 30 seconds
        }

        // Update counter tanpa reload semua notifikasi
        function updateNotificationCounterOnly() {
            $.ajax({
                url: '/notifications/count',
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    updateNotificationCounter(response.unread_count || 0);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal memuat counter notifikasi:', error);
                }
            });
        }

        // Setup keyboard shortcuts
        function setupKeyboardShortcuts() {
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('#notificationDetailModal').modal('hide');
                }
            });
        }

        // Fungsi untuk menampilkan toast notification - DIPERBAIKI
        function showToast(message, type = 'info') {
            // Remove existing toasts
            $('.custom-toast').remove();
            
            const toastClass = type === 'success' ? 'alert-success' : 
                            type === 'error' ? 'alert-danger' : 
                            type === 'warning' ? 'alert-warning' : 'alert-info';
            
            const iconClass = type === 'success' ? 'fa-check-circle' :
                            type === 'error' ? 'fa-exclamation-circle' :
                            type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            
            const toastHtml = `
                <div class="custom-toast alert ${toastClass} alert-dismissible fade show" 
                    style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;">
                    <i class="fas ${iconClass} mr-2"></i>
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            
            $('body').append(toastHtml);
            
            // Auto remove after 4 seconds
            setTimeout(function() {
                $('.custom-toast').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 4000);
        }

        // Make functions globally accessible for debugging
        window.notificationSystem = {
            loadNotifications: loadNotifications,
            deleteAllNotifications: deleteAllNotifications,
            viewNotificationDetail: viewNotificationDetail,
            showToast: showToast
        };
    });
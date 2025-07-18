<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Detail Pelanggan - EVAN NETWORK</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="page-top" style="font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif; color: #5a5c69;">

    <!-- Page Wrapper -->
    <div id="wrapper" style="display: flex;">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="min-height: 100vh; background: linear-gradient(180deg, #4e73df 10%, #224abe 100%); width: 14rem; position: fixed; left: 0; top: 0;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/superadmin/dashboard" style="height: 4.375rem; text-decoration: none; font-size: 1rem; font-weight: 800; padding: 1.5rem 1rem; text-align: center; text-transform: uppercase; color: rgba(255, 255, 255, 0.8);">
                <div class="sidebar-brand-icon" style="font-size: 2rem; margin-right: 0.5rem; transform: rotate(-15deg);">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text">EVAN NETWORK</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider" style="margin: 1rem 0; border-color: rgba(255, 255, 255, 0.15);">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="/superadmin/dashboard" style="display: flex; align-items: center; padding: 1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                    <i class="fas fa-fw fa-tachometer-alt" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" style="margin: 1rem 0; border-color: rgba(255, 255, 255, 0.15);">

            <!-- Heading -->
            <div class="sidebar-heading" style="font-size: 0.65rem; font-weight: 800; padding: 1.5rem 1rem 0.5rem; color: rgba(255, 255, 255, 0.4); text-transform: uppercase;">
                Interface
            </div>

            <!-- Nav Item - Data Pelanggan (Active) -->
            <li class="nav-item active" style="background-color: rgba(255, 255, 255, 0.1);">
                <a class="nav-link" href="/superadmin/datapelanggan" style="display: flex; align-items: center; padding: 1rem; color: #fff; text-decoration: none;">
                    <i class="fas fa-fw fa-users" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Data Pelanggan</span>
                </a>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="display: flex; align-items: center; padding: 1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                    <i class="fas fa-fw fa-cog" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Data-Data</span>
                </a>
                <div id="collapseTwo" class="collapse">
                    <div class="bg-white py-2 collapse-inner rounded" style="background-color: #f8f9fc !important; border-radius: 0.35rem; margin: 0 1rem;">
                        <h6 class="collapse-header" style="margin: 0; padding: 0.75rem 1rem 0.25rem; font-size: 0.65rem; font-weight: 800; color: #b7b9cc; text-transform: uppercase;">Data Pelanggan</h6>
                        <a class="collapse-item" href="/superadmin/datapaket" style="display: block; padding: 0.5rem 1rem; color: #3a3b45; text-decoration: none; font-size: 0.85rem;">Data Paket</a>
                        <a class="collapse-item" href="/superadmin/datapelanggan" style="display: block; padding: 0.5rem 1rem; color: #3a3b45; text-decoration: none; font-size: 0.85rem;">Data Pelanggan</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities" aria-expanded="false" aria-controls="collapseUtilities" style="display: flex; align-items: center; padding: 1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                    <i class="fas fa-fw fa-wrench" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Tagihan</span>
                </a>
                <div id="collapseUtilities" class="collapse">
                    <div class="bg-white py-2 collapse-inner rounded" style="background-color: #f8f9fc !important; border-radius: 0.35rem; margin: 0 1rem;">
                        <h6 class="collapse-header" style="margin: 0; padding: 0.75rem 1rem 0.25rem; font-size: 0.65rem; font-weight: 800; color: #b7b9cc; text-transform: uppercase;">Tagihan:</h6>
                        <a class="collapse-item" href="/superadmin/buattagihan" style="display: block; padding: 0.5rem 1rem; color: #3a3b45; text-decoration: none; font-size: 0.85rem;">Buat Tagihan</a>
                        <a class="collapse-item" href="/superadmin/datatagihan" style="display: block; padding: 0.5rem 1rem; color: #3a3b45; text-decoration: none; font-size: 0.85rem;">Data Tagihan</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/superadmin/pengajuan" style="display: flex; align-items: center; padding: 1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                    <i class="fas fa-fw fa-file-alt" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Pengajuan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/superadmin/pengaduan" style="display: flex; align-items: center; padding: 1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                    <i class="fas fa-fw fa-exclamation-triangle" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Pengaduan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" style="margin: 1rem 0; border-color: rgba(255, 255, 255, 0.15);">

            <!-- Heading -->
            <div class="sidebar-heading" style="font-size: 0.65rem; font-weight: 800; padding: 1.5rem 1rem 0.5rem; color: rgba(255, 255, 255, 0.4); text-transform: uppercase;">Keuangan</div>

            <!-- Nav Item - Laporan Keuangan Menu -->
            <li class="nav-item">
                <a class="nav-link" href="/superadmin/laporankeuangan" style="display: flex; align-items: center; padding: 1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                    <i class="fas fa-fw fa-chart-line" style="width: 0.875rem; text-align: center; margin-right: 0.25rem;"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="margin-left: 14rem; width: calc(100% - 14rem);">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background-color: #fff !important; height: 4.375rem; padding: 0 1.5rem;">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3" style="background: none; border: none; color: #5a5c69;">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" style="background-color: #eaecf4 !important; border: 1px solid #eaecf4; border-radius: 10rem; padding-left: 1rem; height: calc(1.5em + 0.75rem + 2px); font-size: 0.8rem;">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" style="background-color: #4e73df; border-color: #4e73df; border-radius: 0 10rem 10rem 0;">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ms-auto">

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: #5a5c69;">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" style="background-color: #e74a3b; color: white; font-size: 0.65rem; padding: 0.25rem 0.4rem; border-radius: 10rem; position: absolute; top: 0; right: 0; transform: translate(25%, -25%);">3+</span>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block" style="width: 0; height: 2.375rem; border-right: 1px solid #e3e6f0; margin: auto 1rem;"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: #5a5c69; display: flex; align-items: center;">
                                <span class="me-2 d-none d-lg-inline text-gray-600 small">SuperAdmin</span>
                                <img class="img-profile rounded-circle" src="https://via.placeholder.com/60x60" style="height: 2rem; width: 2rem;">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" style="border: 0; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;">
                                <a class="dropdown-item" href="#" style="font-size: 0.85rem; padding: 0.5rem 1.5rem; color: #3a3b45; text-decoration: none;">
                                    <i class="fas fa-user-shield fa-sm fa-fw me-2 text-gray-400"></i>Profil
                                </a>
                                <a class="dropdown-item" href="#" style="font-size: 0.85rem; padding: 0.5rem 1.5rem; color: #3a3b45; text-decoration: none;">
                                    <i class="fas fa-history fa-sm fa-fw me-2 text-gray-400"></i>Log Aktivitas
                                </a>
                                <div class="dropdown-divider" style="margin: 0.5rem 0; border-top: 1px solid #e3e6f0;"></div>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" style="font-size: 0.85rem; padding: 0.5rem 1.5rem; color: #3a3b45; text-decoration: none;">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>Keluar
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">Detail Pelanggan</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb" style="background: none; padding: 0; margin: 0;">
                                    <li class="breadcrumb-item"><a href="/superadmin/dashboard" style="color: #5a5c69; text-decoration: none;">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/superadmin/datapelanggan" style="color: #5a5c69; text-decoration: none;">Data Pelanggan</a></li>
                                    <li class="breadcrumb-item active" aria-current="page" style="color: #858796;">Detail</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <a href="/superadmin/datapelanggan" class="btn btn-secondary btn-sm me-2" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Profile Card -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                                    <h6 class="m-0 font-weight-bold text-primary" style="color: #5a5c69 !important;">
                                        <i class="fas fa-user-circle"></i> Profil Pelanggan
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-4">
                                        <img src="https://via.placeholder.com/120x120" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; border: 3px solid #e3e6f0;">
                                    </div>
                                    <h5 class="card-title mb-1" style="color: #5a5c69;">John Doe</h5>
                                    <p class="text-muted mb-3">@johndoe123</p>
                                    
                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        <span class="badge bg-success" style="background-color: #1cc88a !important; font-size: 0.85rem; padding: 0.5rem 1rem;">
                                            <i class="fas fa-check-circle"></i> Aktif Berlangganan
                                        </span>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="d-grid gap-2">
                                        <a href="#" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit Pelanggan
                                        </a>
                                        <a href="#" class="btn btn-info btn-sm">
                                            <i class="fas fa-history"></i> Riwayat Pengajuan
                                        </a>
                                        <a href="#" class="btn btn-warning btn-sm">
                                            <i class="fas fa-file-invoice-dollar"></i> Buat Tagihan
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                                    <h6 class="m-0 font-weight-bold text-primary" style="color: #5a5c69 !important;">
                                        <i class="fas fa-chart-bar"></i> Statistik Cepat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 border-end">
                                            <div class="h4 mb-0 font-weight-bold text-primary">15</div>
                                            <div class="text-xs text-uppercase text-muted">Bulan Aktif</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="h4 mb-0 font-weight-bold text-success">3</div>
                                            <div class="text-xs text-uppercase text-muted">Total Pengajuan</div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6 border-end">
                                            <div class="h4 mb-0 font-weight-bold text-warning">2</div>
                                            <div class="text-xs text-uppercase text-muted">Tagihan Pending</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="h4 mb-0 font-weight-bold text-info">1</div>
                                            <div class="text-xs text-uppercase text-muted">Pengaduan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Information -->
                        <div class="col-lg-8">
                            <!-- Personal Information -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                                    <h6 class="m-0 font-weight-bold text-primary" style="color: #5a5c69 !important;">
                                        <i class="fas fa-info-circle"></i> Informasi Personal
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">ID Pelanggan</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">#PL001</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">Username</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">johndoe123</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">Nama Lengkap</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">John Doe</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">Email</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">john.doe@email.com</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">No. HP</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">+62 812-3456-7890</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">Tanggal Bergabung</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">15 Januari 2024</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold" style="color: #5a5c69; font-size: 0.9rem;">Alamat</label>
                                            <p class="form-control-plaintext" style="padding: 0.375rem 0; margin-bottom: 0; background: none; border: none; color: #6e707e;">Jl. Merdeka No. 123, RT 02/RW 05, Kelurahan Tegal Barat, Kecamatan Tegal Selatan, Kota Tegal, Jawa Tengah 52114</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Package -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                                    <h6 class="m-0 font-weight-bold text-primary" style="color: #5a5c69 !important;">
                                        <i class="fas fa-wifi"></i> Paket Langganan Aktif
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="mb-2" style="color: #5a5c69;">
                                                <span class="badge bg-primary me-2" style="background-color: #4e73df !important; font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                                    <i class="fas fa-wifi"></i> Paket Premium
                                                </span>
                                            </h5>
                                            <div class="mb-2">
                                                <i class="fas fa-tachometer-alt text-info me-2"></i>
                                                <strong>Kecepatan:</strong> 50 Mbps
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                <strong>Harga:</strong> Rp 350.000/bulan
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-calendar-alt text-warning me-2"></i>
                                                <strong>Mulai Berlangganan:</strong> 15 Januari 2024
                                            </div>
                                            <div>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <strong>Status:</strong> 
                                                <span class="badge bg-success" style="background-color: #1cc88a !important;">Aktif</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-signal" style="font-size: 3rem; color: #1cc88a;"></i>
                                            </div>
                                            <div class="text-muted">
                                                <small>Koneksi Stabil</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                                    <h6 class="m-0 font-weight-bold text-primary" style="color: #5a5c69 !important;">
                                        <i class="fas fa-history"></i> Aktivitas Terbaru
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item mb-3" style="position: relative; padding-left: 2rem;">
                                            <div class="timeline-marker" style="position: absolute; left: 0; top: 0.25rem; width: 0.75rem; height: 0.75rem; background-color: #1cc88a; border-radius: 50%;"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1" style="color: #5a5c69; font-size: 0.9rem;">Pembayaran Berhasil</h6>
                                                <p class="text-muted mb-1" style="font-size: 0.8rem;">Pembayaran tagihan bulan Juni 2025 sebesar Rp 350.000</p>
                                                <small class="text-muted">28 Juni 2025, 14:30</small>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item mb-3" style="position: relative; padding-left: 2rem;">
                                            <div class="timeline-marker" style="position: absolute; left: 0; top: 0.25rem; width: 0.75rem; height: 0.75rem; background-color: #36b9cc; border-radius: 50%;"></div>
                                            <div class="timeline-content">
                                                                                                <h6 class="mb-1" style="color: #5a5c69; font-size: 0.9rem;">Pengajuan Paket Baru</h6>
                                                <p class="text-muted mb-1" style="font-size: 0.8rem;">Pengajuan untuk upgrade ke Paket Premium 100 Mbps</p>
                                                <small class="text-muted">25 Juni 2025, 11:45</small>
                                            </div>
                                        </div>

                                        <div class="timeline-item mb-3" style="position: relative; padding-left: 2rem;">
                                            <div class="timeline-marker" style="position: absolute; left: 0; top: 0.25rem; width: 0.75rem; height: 0.75rem; background-color: #f6c23e; border-radius: 50%;"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1" style="color: #5a5c69; font-size: 0.9rem;">Pengaduan Kecepatan Internet</h6>
                                                <p class="text-muted mb-1" style="font-size: 0.8rem;">Pelanggan melaporkan keluhan terkait kecepatan internet yang tidak stabil</p>
                                                <small class="text-muted">20 Juni 2025, 16:20</small>
                                            </div>
                                        </div>

                                        <div class="timeline-item mb-3" style="position: relative; padding-left: 2rem;">
                                            <div class="timeline-marker" style="position: absolute; left: 0; top: 0.25rem; width: 0.75rem; height: 0.75rem; background-color: #e74a3b; border-radius: 50%;"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1" style="color: #5a5c69; font-size: 0.9rem;">Tagihan Terlambat</h6>
                                                <p class="text-muted mb-1" style="font-size: 0.8rem;">Tagihan bulan Mei 2025 belum dibayar setelah 15 hari jatuh tempo</p>
                                                <small class="text-muted">18 Juni 2025, 10:00</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

    </div>
    <!-- End of Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Keluar" di bawah ini jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
</body>

</html>

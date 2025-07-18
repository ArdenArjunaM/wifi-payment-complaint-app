<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard Teknisi EVAN NETWORK - Sistem Manajemen WiFi">
    <meta name="author" content="EVAN NETWORK">

    <title>Dashboard Teknisi - EVAN NETWORK</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/teknisi">
            <div class="sidebar-brand-icon">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="sidebar-brand-text mx-3">EVAN NETWORK</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="/teknisi">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">Layanan Teknisi</div>

        <!-- Nav Item - Pengajuan -->
        <li class="nav-item">
            <a class="nav-link" href="/teknisi/pengajuan">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Pengajuan</span>
            </a>
        </li>

        <!-- Nav Item - Pengaduan -->
        <li class="nav-item">
            <a class="nav-link" href="/teknisi/pengaduan">
                <i class="fas fa-fw fa-exclamation-triangle"></i>
                <span>Pengaduan</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->
    
    <!-- Rest of the content remains unchanged -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-bell fa-fw"></i>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" id="notifications-dropdown">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <!-- Notifications will be dynamically inserted here -->
                                <a class="dropdown-item text-center small text-gray-500" href="#">Lihat Semua Notifikasi</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Teknisi {{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i>Profil Teknisi
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-400"></i>Peralatan Kerja
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-history fa-sm fa-fw mr-2 text-gray-400"></i>Riwayat Pekerjaan
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Keluar
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Teknisi</h1>
                        <p class="mb-0 text-gray-600">Selamat datang di panel teknisi EVAN NETWORK</p>
                    </div>

                    <!-- Welcome Card -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow">
                                <div class="card-body text-center py-5">
                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="feature-item">
                                                <div class="mb-3">
                                                    <i class="fas fa-tools fa-3x text-primary"></i>
                                                </div>
                                                <h5 class="font-weight-bold">Kelola Pekerjaan</h5>
                                                <p class="text-gray-600">Pantau dan kelola semua tugas teknis yang diberikan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="feature-item">
                                                <div class="mb-3">
                                                    <i class="fas fa-file-alt fa-3x text-success"></i>
                                                </div>
                                                <h5 class="font-weight-bold">Pengajuan Layanan</h5>
                                                <p class="text-gray-600">Proses pengajuan pemasangan dan upgrade dari pelanggan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="feature-item">
                                                <div class="mb-3">
                                                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                                </div>
                                                <h5 class="font-weight-bold">Pengaduan Teknis</h5>
                                                <p class="text-gray-600">Tangani keluhan dan masalah teknis pelanggan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6 mb-3">
                                            <a href="/teknisi/pengajuan" class="btn btn-primary btn-lg btn-block">
                                                <i class="fas fa-file-alt mr-2"></i>Lihat Pengajuan
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="/teknisi/pengaduan" class="btn btn-warning btn-lg btn-block">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>Lihat Pengaduan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Access Panel -->
                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tugas Hari Ini</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-3x text-gray-400 mb-3"></i>
                                        <h5 class="text-gray-700">Belum ada tugas untuk hari ini</h5>
                                        <p class="text-gray-500 mb-4">Semua pengajuan dan pengaduan akan muncul di sini</p>
                                        <div class="row">
                                            <div class="col-6">
                                                <a href="/pengajuan" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-plus mr-1"></i>Cek Pengajuan
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="/pengaduan" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-search mr-1"></i>Cek Pengaduan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Status Teknisi</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="badge badge-success badge-lg px-3 py-2">
                                            <i class="fas fa-check-circle mr-1"></i>Online
                                        </span>
                                    </div>
                                    <h6 class="text-gray-700">Siap Menerima Tugas</h6>
                                    <p class="text-gray-500 small mb-3">Last active: {{ date('d M Y, H:i') }}</p>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-cog mr-1"></i>Ubah Status
                                    </button>
                                </div>
                            </div>

                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Menu Cepat</h6>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="/pengajuan" class="list-group-item list-group-item-action border-0 px-0">
                                            <i class="fas fa-file-alt text-primary mr-2"></i>Pengajuan Baru
                                        </a>
                                        <a href="/pengaduan" class="list-group-item list-group-item-action border-0 px-0">
                                            <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Pengaduan Aktif
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                            <i class="fas fa-tools text-info mr-2"></i>Peralatan Kerja
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                            <i class="fas fa-history text-secondary mr-2"></i>Riwayat Tugas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities - Simplified -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
                                    <small class="text-gray-500">Update terakhir: {{ date('H:i') }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-gray-400 mb-3"></i>
                                        <h6 class="text-gray-600">Belum ada aktivitas terbaru</h6>
                                        <p class="text-gray-500 small">Pengajuan dan pengaduan baru akan muncul di sini</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; EVAN NETWORK 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

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
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Keluar" di bawah ini jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <script src="{{ asset('js/demo/dashboard-stats.js') }}"></script>
    
</body>
</html>
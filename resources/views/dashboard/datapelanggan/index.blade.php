<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Data Pelanggan</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">EVAN NETWORK</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/admin">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Data-Data</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Data Pelanggan</h6>
                        <a class="collapse-item" href="/datapaket">Data Paket</a>
                        <a class="collapse-item" href="/datapelanggan">Data Pelanggan</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Tagihan</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tagihan:</h6>
                        <a class="collapse-item" href="/datatagihan/create">Buat Tagihan</a>
                        <a class="collapse-item" href="/datatagihan">Data Tagihan</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="/pengajuan" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Pengajuan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="/pengaduan" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Pengaduan</span>
                </a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <di id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <d class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Data Pelanggan</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <div class="row">

    <!-- Total Paket Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Paket</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPaket ?? 0 }}</div>  <!-- Menampilkan total paket -->
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pelanggan Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pelanggan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers ?? 0 }}</div>  <!-- Menampilkan total pelanggan -->
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Belum Dibayar Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Belum Dibayar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $belumDibayar ?? 0 }}</div>  <!-- Menampilkan tagihan yang belum dibayar -->
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lunas Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Lunas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lunas ?? 0 }}</div>  <!-- Menampilkan tagihan yang lunas -->
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i> <!-- Ubah ikon jika perlu -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

                 <!-- Content Row -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Pelanggan</h6>
        <!-- Tombol Tambah Pelanggan -->
        <a href="{{ route('superadmin.datapelanggan.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </a>
    </div>
    
    <!-- Search Section -->
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('dashboard.datapelanggan.index') }}" id="searchForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari nama, username, email, atau nomor HP..." 
                               value="{{ request('search') }}"
                               onchange="document.getElementById('searchForm').submit()">
                        <div class="input-group-append">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control" onchange="document.getElementById('searchForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Menunggu" {{ request('status') == 'Pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Belum Mengajukan" {{ request('status') == 'Belum Mengajukan' ? 'selected' : '' }}>Belum Mengajukan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('dashboard.datapelanggan.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Menampilkan {{ isset($pelanggan) ? $pelanggan->count() : 0 }} pelanggan
                    @if(request('search') || request('status'))
                        dari pencarian: 
                        @if(request('search'))
                            <strong>"{{ request('search') }}"</strong>
                        @endif
                        @if(request('status'))
                            <strong>Status: {{ request('status') }}</strong>
                        @endif
                    @endif
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Paket Langganan</th>
                        <th>Status Berlangganan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($pelanggan) && $pelanggan->count() > 0)
                        @foreach ($pelanggan as $item)
                            <tr>
                                <td>{{ $item->id_user }}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td>
                                    @php
                                        // Cek paket dari pengajuan yang disetujui atau selesai
                                        $pengajuanAktif = $item->pengajuan()->whereHas('statusPengajuan', function($query) {
                                            $query->whereIn('status', ['Disetujui', 'Selesai']);
                                        })->with('paketWifi')->latest()->first();
                                    @endphp
                                    
                                    @if($pengajuanAktif && $pengajuanAktif->paketWifi)
                                        <div class="d-flex flex-column">
                                            <span class="badge badge-primary mb-1">
                                                <i class="fas fa-wifi"></i> {{ $pengajuanAktif->paketWifi->nama_paket }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-tachometer-alt"></i> {{ $pengajuanAktif->paketWifi->kecepatan ?? 'N/A' }}
                                                @if(isset($pengajuanAktif->paketWifi->harga))
                                                    <br><i class="fas fa-money-bill-wave"></i> Rp {{ number_format($pengajuanAktif->paketWifi->harga, 0, ',', '.') }}/bulan
                                                @endif
                                            </small>
                                        </div>
                                    @elseif($item->pengajuan()->exists())
                                        @php
                                            $pengajuanTerbaru = $item->pengajuan()->with('paketWifi')->latest()->first();
                                        @endphp
                                        @if($pengajuanTerbaru && $pengajuanTerbaru->paketWifi)
                                            <div class="d-flex flex-column">
                                                <span class="badge badge-secondary mb-1">
                                                    <i class="fas fa-wifi"></i> {{ $pengajuanTerbaru->paketWifi->nama_paket }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="fas fa-hourglass-half"></i> Menunggu Persetujuan
                                                </small>
                                            </div>
                                        @else
                                            <span class="badge badge-light">
                                                <i class="fas fa-question-circle"></i> Paket Tidak Ditemukan
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge badge-light">
                                            <i class="fas fa-minus-circle"></i> Belum Ada Paket
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        // Mengambil status pengajuan terbaru berdasarkan created_at
                                        $pengajuanTerbaru = $item->pengajuan()->with('statusPengajuan')->orderBy('created_at', 'desc')->first();
                                        $statusPengajuan = $pengajuanTerbaru ? $pengajuanTerbaru->statusPengajuan->status : null;
                                    @endphp

                                    @if($statusPengajuan == 'Selesai')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-double"></i> Selesai
                                        </span>
                                    @elseif($statusPengajuan == 'Disetujui')
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge badge-info mb-1">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                            <!-- Tombol untuk mengubah status ke Selesai -->
                                            @if($pengajuanTerbaru && $pengajuanTerbaru->id)
                                                <form action="{{ route('dashboard.pengajuan.selesai', $pengajuanTerbaru->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success px-2 py-1" 
                                                            style="font-size: 10px; line-height: 1.2;"
                                                            onclick="return confirm('Yakin ingin menandai sebagai selesai?')" 
                                                            title="Tandai Selesai">
                                                        <i class="fas fa-check" style="font-size: 8px;"></i> Selesai
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif($statusPengajuan == 'Pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Menunggu Persetujuan
                                        </span>
                                    @elseif($statusPengajuan == 'Ditolak')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    @elseif($statusPengajuan)
                                        <span class="badge badge-info">
                                            <i class="fas fa-info-circle"></i> {{ $statusPengajuan }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-user-plus"></i> Belum Mengajukan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Tombol Detail dengan informasi pengajuan -->
                                    <a href="{{ route('dashboard.datapelanggan.show', $item->id_user) }}" class="btn btn-info btn-sm mb-1" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('dashboard.datapelanggan.edit', $item->id_user) }}" class="btn btn-warning btn-sm mb-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Tombol Riwayat Pengajuan -->
                                    @if($item->pengajuan()->exists())
                                        <a href="{{ route('dashboard.pengajuan.index', ['pelanggan' => $item->id_user]) }}" class="btn btn-secondary btn-sm mb-1" title="Riwayat Pengajuan">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    @endif
                                    
                                    <!-- Form Hapus -->
                                    <form action="{{ route('dashboard.datapelanggan.destroy', $item->id_user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pelanggan ini? Semua data pengajuan juga akan terhapus!')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                Tidak ada data pelanggan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>




            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
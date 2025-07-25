<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Data Tagihan</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Data Tagihan</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">Daftar Tagihan</h6>
        
        <!-- Tombol Buat Tagihan Manual, Generate, dan Export Dropdown -->
        <div class="d-flex flex-wrap gap-2">

            <!-- Buat Tagihan Manual -->
            <a href="{{ route('dashboard.datatagihan.create') }}" class="btn btn-info btn-sm">
                <i class="fas fa-plus"></i> Buat Tagihan Manual
            </a>

            <!-- Generate Tagihan Bulanan -->
            <button class="btn btn-secondary btn-sm" onclick="generateMonthlyBills()" title="Generate tagihan bulanan untuk semua pelanggan aktif">
                <i class="fas fa-calendar-alt"></i> Generate Tagihan Bulanan
            </button>

            <!-- Export Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download"></i> Export Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('tagihan.export.pdf') }}">
                            <i class="fas fa-file-pdf text-danger me-2"></i> Export PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('tagihan.export.excel') }}">
                            <i class="fas fa-file-excel text-success me-2"></i> Export Excel
                        </a>
                    </li>
                </ul>
            </div>

        </div>
   


    </div>
    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter dan Search -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-control" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar">Belum Bayar</option>
                    <option value="lunas">Lunas</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filterBulan">
                    <option value="">Semua Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari nama pelanggan..." id="searchPelanggan">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
<!-- Scrollable Table -->
<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>ID Tagihan</th>
                <th>Nama Pelanggan</th>
                <th>Paket WiFi</th>
                <th>Periode</th>
                <th>Jumlah Tagihan</th>
                <th>Jatuh Tempo</th>
                <th>Status Tagihan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($tagihan) && $tagihan->count() > 0)
                @foreach($tagihan as $item)
                    @php
                        // Menggunakan status_tagihan_id sesuai dengan PaymentController
                        $statusTagihanId = $item->status_tagihan_id ?? 1;
                        $isLunas = ($statusTagihanId == 3);
                        $jatuhTempo = \Carbon\Carbon::parse($item->jatuh_tempo);
                        $isPast = $jatuhTempo->isPast();
                        $daysDiff = $jatuhTempo->diffInDays(now());
                    @endphp
                    
                    <tr class="{{ $isPast && !$isLunas ? 'table-warning' : '' }}">
                        <td>
                            <strong>{{ $item->id_tagihan }}</strong>
                            @if($item->is_auto_generated ?? false)
                                <br><small class="badge badge-info">Auto</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <strong>{{ $item->user->nama ?? 'N/A' }}</strong>
                                <small class="text-muted">{{ $item->user->username ?? 'N/A' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge badge-primary">
                                    <i class="fas fa-wifi"></i> {{ $item->paketWifi->nama_paket ?? 'N/A' }}
                                </span>
                                @if($item->paketWifi)
                                    <small class="text-muted">{{ $item->paketWifi->kecepatan ?? 'N/A' }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if(isset($item->periode_tagihan))
                                <strong>{{ \Carbon\Carbon::parse($item->periode_tagihan)->format('M Y') }}</strong>
                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($item->periode_tagihan)->format('d/m/Y') }}</small>
                            @else
                                <strong>{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('M Y') }}</strong>
                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/Y') }}</small>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success">Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</strong>
                            @if(($item->denda ?? 0) > 0)
                                <br><small class="text-danger">+ Denda: Rp {{ number_format($item->denda, 0, ',', '.') }}</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $isNextMonth = $jatuhTempo->isNextMonth();
                            @endphp
                            <div class="d-flex flex-column">
                                <strong class="{{ $isPast ? 'text-danger' : ($daysDiff <= 3 ? 'text-warning' : 'text-dark') }}">
                                    {{ $jatuhTempo->format('d/m/Y') }}
                                </strong>
                                <small class="text-muted">
                                    @if($isPast)
                                        <i class="fas fa-exclamation-triangle text-danger"></i> Terlambat {{ $daysDiff }} hari
                                    @elseif($isNextMonth)
                                        <i class="fas fa-calendar text-success"></i> Bulan Depan
                                    @elseif($daysDiff <= 3)
                                        <i class="fas fa-clock text-warning"></i> {{ $daysDiff }} hari lagi
                                    @else
                                        <i class="fas fa-calendar text-success"></i> {{ $daysDiff }} hari lagi
                                    @endif
                                </small>
                            </div>
                        </td>

                        <td>
                           @php
                                // Sesuai dengan mapping di PaymentController
                                $statusDisplay = match($statusTagihanId) {
                                    3 => ['icon' => 'fas fa-check-circle', 'text' => 'Lunas', 'color' => 'text-success'],
                                    1 => ['icon' => 'fas fa-clock', 'text' => 'Belum Dibayar', 'color' => 'text-danger'],
                                    default => ['icon' => 'fas fa-clock', 'text' => 'Belum Dibayar', 'color' => 'text-danger']
                                };
                                
                                // Tambahan logika untuk terlambat
                                if ($statusTagihanId == 1 && $isPast) {
                                    $statusDisplay = ['icon' => 'fas fa-exclamation-triangle', 'text' => 'Terlambat', 'color' => 'text-danger'];
                                }
                            @endphp
                            <span class="{{ $statusDisplay['color'] }}">
                                <i class="{{ $statusDisplay['icon'] }}"></i> {{ $statusDisplay['text'] }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group-vertical" role="group">
                                
                                @if(!$isLunas)
                                    <!-- Tombol Kirim Tagihan -->
                                    @if(Route::has('dashboard.datatagihan.kirim'))
                                        <a href="{{ route('dashboard.datatagihan.kirim', $item->id_tagihan) }}" 
                                           class="btn btn-success btn-sm mb-1" title="Kirim Tagihan">
                                            <i class="fas fa-paper-plane"></i> Kirim
                                        </a>
                                    @endif
                                    
                                    <!-- Tombol Tandai Lunas -->
                                    @if(Route::has('dashboard.datatagihan.markAsPaid'))
                                        <form action="{{ route('dashboard.datatagihan.markAsPaid', $item->id_tagihan) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-info btn-sm mb-1" 
                                                    onclick="return confirm('Tandai tagihan ini sebagai lunas?')" 
                                                    title="Tandai Lunas">
                                                <i class="fas fa-check"></i> Lunas
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                <!-- Tombol Detail -->
                                @if(Route::has('dashboard.datatagihan.show'))
                                    <a href="{{ route('dashboard.datatagihan.show', $item->id_tagihan) }}" 
                                       class="btn btn-primary btn-sm mb-1" title="Detail Tagihan">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                @endif

                                <!-- Tombol Edit -->
                                @if(Route::has('dashboard.datatagihan.edit'))
                                    <a href="{{ route('dashboard.datatagihan.edit', $item->id_tagihan) }}" 
                                       class="btn btn-warning btn-sm mb-1" title="Edit Tagihan">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif

                                <!-- Tombol Hapus -->
                                @if(Route::has('dashboard.datatagihan.destroy'))
                                    
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="fas fa-file-invoice fa-2x mb-2"></i><br>
                        Tidak ada tagihan yang tersedia.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
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

    <script src="{{ asset('js/tagihan.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <!-- Export Tagihan JavaScript -->
    <script src="{{ asset('js/export-tagihan.js') }}"></script> 

</body>

</html>

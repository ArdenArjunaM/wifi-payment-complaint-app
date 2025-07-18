<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Riwayat Pembayaran - EVAN NETWORK</title>

    <!-- Link to FontAwesome and Google Fonts -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../css/sb-admin-2.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">EVAN NETWORK</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Paket Wifi -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="/paketwifi" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Paket Wifi</span>
                </a>
            </li>

            <!-- Nav Item - Tagihan -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="/tagihan"  data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Tagihan</span>
                </a>
            </li>

            <!-- Nav Item - Pengaduan -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="/pengaduanwifi" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Pengaduan</span>
                </a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->username }}</span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>

                </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-history"></i> Riwayat Pembayaran
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="/tagihan">Tagihan</a></li>
                                <li class="breadcrumb-item active">Riwayat Pembayaran</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.tagihan') }}">
                                        <i class="fas fa-list"></i> Semua Tagihan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('user.tagihan.history') }}">
                                        <i class="fas fa-history"></i> Riwayat Pembayaran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.tagihan.reminder') }}">
                                        <i class="fas fa-bell"></i> Pengingat
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Filter dan Statistik -->
                    <div class="row mb-4">
                        <!-- Filter -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-filter"></i> Filter Riwayat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('user.tagihan.history') }}">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="tahun">Tahun:</label>
                                                <select class="form-control" id="tahun" name="tahun">
                                                    <option value="">Semua Tahun</option>
                                                    <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>2024</option>
                                                    <option value="2023" {{ request('tahun') == '2023' ? 'selected' : '' }}>2023</option>
                                                    <option value="2022" {{ request('tahun') == '2022' ? 'selected' : '' }}>2022</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="bulan">Bulan:</label>
                                                <select class="form-control" id="bulan" name="bulan">
                                                    <option value="">Semua Bulan</option>
                                                    <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                                                    <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                                                    <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                                                    <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                                                    <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                                                    <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                                                    <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                                                    <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                                                    <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                                                    <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                                    <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                                                    <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>&nbsp;</label>
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary mr-2">
                                                        <i class="fas fa-search"></i> Filter
                                                    </button>
                                                    <a href="{{ route('user.tagihan.history') }}" class="btn btn-secondary">
                                                        <i class="fas fa-refresh"></i> Reset
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Pembayaran</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $riwayatPembayaran->count() }}
                                            </div>
                                            <div class="text-xs text-muted mt-1">
                                                Rp {{ number_format($riwayatPembayaran->sum('jumlah_tagihan'), 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Riwayat Pembayaran -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-receipt"></i> Riwayat Pembayaran Anda
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <a class="dropdown-item" href="{{ route('user.tagihan.history.export', ['format' => 'pdf']) }}">
                                        <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                    </a>
                                    <a class="dropdown-item" href="{{ route('user.tagihan.history.export', ['format' => 'excel']) }}">
                                        <i class="fas fa-file-excel text-success"></i> Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($riwayatPembayaran->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No. Invoice</th>
                                                <th>Paket WiFi</th>
                                                <th>Periode</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Jumlah</th>
                                                <th>Metode Bayar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($riwayatPembayaran as $index => $item)
                                                <tr>
                                                    <td>
                                                        <strong>INV{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('Y') }}-{{ str_pad($item->id_tagihan, 3, '0', STR_PAD_LEFT) }}</strong>
                                                    </td>
                                                    <td>
                                                        {{ $item->paketWifi->nama_paket ?? 'N/A' }}<br>
                                                        <small class="text-muted">{{ $item->paketWifi->kecepatan ?? 'N/A' }} Mbps</small>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_tagihan)->format('M Y') }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('d M Y') }}<br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</strong>
                                                        @if($item->denda > 0)
                                                            <br><small class="text-warning">+ Denda Rp {{ number_format($item->denda, 0, ',', '.') }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->metode_pembayaran == 'midtrans')
                                                            <span class="badge badge-info">Midtrans</span>
                                                        @elseif($item->metode_pembayaran == 'manual')
                                                            <span class="badge badge-secondary">Manual</span>
                                                        @else
                                                            <span class="badge badge-primary">{{ ucfirst($item->metode_pembayaran ?? 'Online') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle"></i> Lunas
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <!-- Tombol Lihat Detail -->
                                                            <a href="{{ route('user.tagihan.show', $item->id_tagihan) }}" 
                                                               class="btn btn-info btn-sm" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            
                                                            <!-- Tombol Download Invoice -->
                                                            <a href="{{ route('user.tagihan.invoice', $item->id_tagihan) }}" 
                                                               class="btn btn-primary btn-sm" title="Download Invoice" target="_blank">
                                                                <i class="fas fa-download"></i>
                                                            </a>

                                                            <!-- Tombol Print Invoice -->
                                                            <button class="btn btn-secondary btn-sm print-invoice" 
                                                                    data-invoice-url="{{ route('user.tagihan.invoice', $item->id_tagihan) }}"
                                                                    title="Print Invoice">
                                                                <i class="fas fa-print"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination Links -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $riwayatPembayaran->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-receipt fa-3x text-gray-300 mb-3"></i>
                                    <h5 class="text-gray-600">Belum Ada Riwayat Pembayaran</h5>
                                    <p class="text-muted">Anda belum memiliki riwayat pembayaran pada periode yang dipilih.</p>
                                    <a href="{{ route('user.tagihan') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tagihan
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Ringkasan Pembayaran Per Bulan -->
                    @if($riwayatPembayaran->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-chart-bar"></i> Ringkasan Pembayaran Bulanan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $groupedByMonth = $riwayatPembayaran->groupBy(function($item) {
                                        return \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('Y-m');
                                    })->sortKeysDesc();
                                @endphp
                                
                                @foreach($groupedByMonth->take(6) as $month => $payments)
                                    @php
                                        $monthName = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
                                        $totalAmount = $payments->sum('jumlah_tagihan');
                                        $totalCount = $payments->count();
                                    @endphp
                                    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                            {{ $monthName }}
                                                        </div>
                                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                            {{ $totalCount }} Tagihan
                                                        </div>
                                                        <div class="text-xs text-muted">
                                                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

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

    <!-- Script Libraries -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <!-- Custom Script untuk Print -->
    <script>
        $(document).ready(function() {
            // Fungsi Print Invoice
            $('.print-invoice').click(function() {
                var invoiceUrl = $(this).data('invoice-url');
                window.open(invoiceUrl, '_blank');
            });
        });
    </script>

</body>
</html>
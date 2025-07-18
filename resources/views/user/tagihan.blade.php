<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Daftar Tagihan - EVAN NETWORK</title>

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

                    <!-- Info Message -->
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-file-invoice-dollar"></i> Daftar Tagihan
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active">Tagihan</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('user.tagihan') }}">
                                        <i class="fas fa-list"></i> Semua Tagihan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.tagihan.history') }}">
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

                    <!-- Statistik Tagihan -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Sudah Dibayar</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $tagihan->where('status_tagihan_id', 3)->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Tagihan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $tagihan->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Tagihan -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-list"></i> Daftar Tagihan Anda
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($tagihan->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No. Invoice</th>
                                                <th>Paket WiFi</th>
                                                <th>Periode</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tagihan as $item)
                                                <tr>
                                                    <td>
                                                        <strong>INV{{ date('Y') }}-{{ str_pad($item->id_tagihan, 3, '0', STR_PAD_LEFT) }}</strong>
                                                    </td>
                                                    <td>
                                                        {{ $item->paketWifi->nama_paket ?? 'N/A' }}<br>
                                                        <small class="text-muted">{{ $item->paketWifi->kecepatan ?? 'N/A' }} </small>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_tagihan)->format('M Y') }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d M Y') }}
                                                        @if(\Carbon\Carbon::parse($item->jatuh_tempo)->isPast() && $item->status_tagihan_id != 3)
                                                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Lunas</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</strong>
                                                        @if(\Carbon\Carbon::parse($item->jatuh_tempo)->isPast() && $item->status_tagihan_id != 3)
                                                            <br><small class="text-danger">+ Denda Rp 5.000</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->status_tagihan_id == 1)
                                                            <span class="badge badge-warning">Belum Dibayar</span>
                        
                                                        @elseif($item->status_tagihan_id == 3)
                                                            <span class="badge badge-success">Lunas</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <!-- Tombol Lihat Detail -->
                                                            <a href="{{ route('user.tagihan.show', $item->id_tagihan) }}" 
                                                               class="btn btn-info btn-sm" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            
                                                            @if(in_array($item->status_tagihan_id, [1]))
                                                                <!-- Tombol Bayar -->
                                                                <button class="btn btn-success btn-sm pay-btn" 
                                                                        data-tagihan-id="{{ $item->id_tagihan }} "
                                                                        data-amount="{{ $item->jumlah_tagihan }}"
                                                                        data-invoice="INV{{ date('Y') }}-{{ str_pad($item->id_tagihan, 3, '0', STR_PAD_LEFT) }}"
                                                                        data-paket="{{ $item->paketWifi->nama_paket ?? 'N/A' }}"
                                                                        title="Bayar Tagihan">
                                                                    <i class="fas fa-credit-card"></i>
                                                                </button>
                                                            @else
                                                                <!-- Tombol Download Invoice -->
                                                                <a href="{{ route('user.tagihan.invoice', $item->id_tagihan) }}" 
                                                                   class="btn btn-primary btn-sm" title="Download Invoice" target="_blank">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination Links -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $tagihan->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-file-invoice fa-3x text-gray-300 mb-3"></i>
                                    <h5 class="text-gray-600">Belum Ada Tagihan</h5>
                                    <p class="text-muted">Anda belum memiliki tagihan yang perlu dibayar.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5>Memproses Pembayaran...</h5>
                    <p class="text-muted">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">Terjadi kesalahan dalam pembayaran.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
    
    <!-- Midtrans Snap Script -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <!-- Payment JavaScript -->
    <script src="../js/payment.js"></script>

</body>
</html>

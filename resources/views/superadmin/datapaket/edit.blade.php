@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit Paket Wifi - EVAN NETWORK</title>
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/superadmin/dashboard">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">EVAN NETWORK</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
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
            <li class="nav-item active">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Data-Data</span>
                </a>
                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Data Pelanggan</h6>
                        <a class="collapse-item active" href="/superadmin/datapaket">Data Paket</a>
                        <a class="collapse-item" href="/superadmin/datapelanggan">Data Pelanggan</a>
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
                        <a class="collapse-item" href="/superadmin/datatagihan/create">Buat Tagihan</a>
                        <a class="collapse-item" href="/superadmin/datatagihan">Data Tagihan</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="/superadmin/pengajuan" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Pengajuan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="/superadmin/pengaduan" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Pengaduan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Laporan Keuangan Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/superadmin/laporankeuangan" >
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Laporan Keuangan</span>
            </a>

        </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

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
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">SuperAdmin</span>
                                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Paket Wifi</h1>
                        <a href="{{ route('superadmin.datapaket.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Data Paket
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan dalam pengisian form:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Form Edit Paket -->
                    @if(isset($paketWifi))
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-edit"></i> Form Edit Paket Wifi
                                </h6>
                                <span class="badge badge-info">ID: {{ $paketWifi->id_paket_wifi }}</span>
                            </div>
                            
                            <div class="card-body">
                                <form action="{{ route('superadmin.datapaket.update', $paketWifi->id_paket_wifi) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-lg-8">
                                            <!-- Nama Paket -->
                                            <div class="form-group">
                                                <label for="nama_paket" class="form-label font-weight-bold">
                                                    <i class="fas fa-wifi text-primary"></i> Nama Paket
                                                </label>
                                                <input 
                                                    type="text" 
                                                    class="form-control @error('nama_paket') is-invalid @enderror" 
                                                    id="nama_paket" 
                                                    name="nama_paket" 
                                                    value="{{ old('nama_paket', $paketWifi->nama_paket) }}" 
                                                    placeholder="Masukkan nama paket wifi"
                                                    required>
                                            </div>

                                            <!-- Kecepatan -->
                                            <div class="form-group">
                                                <label for="kecepatan" class="form-label font-weight-bold">
                                                    <i class="fas fa-tachometer-alt text-success"></i> Kecepatan
                                                </label>
                                                <div class="input-group">
                                                    <input 
                                                        type="text" 
                                                        class="form-control @error('kecepatan') is-invalid @enderror" 
                                                        id="kecepatan" 
                                                        name="kecepatan" 
                                                        value="{{ old('kecepatan', $paketWifi->kecepatan) }}" 
                                                        placeholder="Contoh: 10 Mbps"
                                                        required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Mbps</span>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            <!-- Harga Bulanan -->
                                            <div class="form-group">
                                                <label for="harga_bulanan" class="form-label font-weight-bold">
                                                    <i class="fas fa-money-bill-wave text-warning"></i> Harga Bulanan
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input 
                                                        type="number" 
                                                        class="form-control @error('harga_bulanan') is-invalid @enderror" 
                                                        id="harga_bulanan" 
                                                        name="harga_bulanan" 
                                                        value="{{ old('harga_bulanan', $paketWifi->harga_bulanan) }}" 
                                                        placeholder="Masukkan harga bulanan"
                                                        min="0"
                                                        required>
                                                    
                                                </div>
                                            </div>

                                            <!-- Deskripsi Paket -->
                                            <div class="form-group">
                                                <label for="deskripsi_paket" class="form-label font-weight-bold">
                                                    <i class="fas fa-align-left text-info"></i> Deskripsi Paket
                                                </label>
                                                <textarea 
                                                    class="form-control @error('deskripsi_paket') is-invalid @enderror" 
                                                    id="deskripsi_paket" 
                                                    name="deskripsi_paket" 
                                                    rows="4" 
                                                    placeholder="Masukkan deskripsi paket wifi"
                                                    required>{{ old('deskripsi_paket', $paketWifi->deskripsi_paket) }}</textarea>
                                                
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <!-- Gambar -->
                                            <div class="form-group">
                                                <label for="gambar" class="form-label font-weight-bold">
                                                    <i class="fas fa-image text-danger"></i> Gambar Paket
                                                </label>
                                                <input 
                                                    type="file" 
                                                    class="form-control-file @error('gambar') is-invalid @enderror" 
                                                    id="gambar" 
                                                    name="gambar" 
                                                    accept="image/*"
                                                    onchange="previewImage(this)">
                                                <small class="form-text text-muted">
                                                    Format: JPG, PNG, GIF. Maksimal 2MB.
                                                </small>
                                                

                                                <!-- Preview Gambar -->
                                                <div class="mt-3">
                                                    @if($paketWifi->gambar)
                                                        <div class="card">
                                                            <div class="card-header py-2">
                                                                <small class="font-weight-bold text-muted">Gambar Saat Ini:</small>
                                                            </div>
                                                            <div class="card-body p-2 text-center">
                                                                <img 
                                                                    src="{{ asset('storage/' . $paketWifi->gambar) }}" 
                                                                    alt="{{ $paketWifi->nama_paket }}" 
                                                                    class="img-fluid rounded shadow-sm"
                                                                    style="max-height: 200px; object-fit: cover;"
                                                                    id="currentImage">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="card">
                                                            <div class="card-body p-3 text-center text-muted">
                                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                                <p class="mb-0">Belum ada gambar</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Preview untuk gambar baru -->
                                                    <div class="card mt-2" id="newImagePreview" style="display: none;">
                                                        <div class="card-header py-2">
                                                            <small class="font-weight-bold text-success">Preview Gambar Baru:</small>
                                                        </div>
                                                        <div class="card-body p-2 text-center">
                                                            <img 
                                                                id="previewImg" 
                                                                class="img-fluid rounded shadow-sm"
                                                                style="max-height: 200px; object-fit: cover;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Submit Buttons -->
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('superadmin.datapaket.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-warning mr-2">
                                                <i class="fas fa-undo"></i> Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="card shadow mb-4">
                            <div class="card-body text-center">
                                <div class="text-muted">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                    <h5>Data Paket Tidak Ditemukan</h5>
                                    <p>Paket wifi yang ingin Anda edit tidak ditemukan atau telah dihapus.</p>
                                    <a href="{{ route('superadmin.datapaket.index') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Data Paket
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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

    <!-- Custom Script for Image Preview -->
    <script>
        function previewImage(input) {
            const file = input.files[0];
            const previewContainer = document.getElementById('newImagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Format number input for harga_bulanan
        document.getElementById('harga_bulanan').addEventListener('input', function(e) {
            let value = e.target.value;
            // Remove any non-numeric characters except for the decimal point
            value = value.replace(/[^0-9]/g, '');
            e.target.value = value;
        });
    </script>

</body>

</html>
@endsection
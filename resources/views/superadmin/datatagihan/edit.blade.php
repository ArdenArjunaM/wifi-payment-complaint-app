<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Edit Tagihan - EVAN NETWORK</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
<link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
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
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Data-Data</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Data Pelanggan</h6>
                        <a class="collapse-item" href="/superadmin/datapaket">Data Paket</a>
                        <a class="collapse-item" href="/superadmin/datapelanggan">Data Pelanggan</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Tagihan</span>
                </a>
                <div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tagihan:</h6>
                        <a class="collapse-item" href="/superadmin/datatagihan/create">Buat Tagihan</a>
                        <a class="collapse-item active" href="/superadmin/datatagihan">Data Tagihan</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="/suoeradmin/pengajuan" data-target="#collapseUtilities"
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

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user-shield fa-sm fa-fw mr-2 text-gray-400"></i>Profil
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-history fa-sm fa-fw mr-2 text-gray-400"></i>Log Aktivitas
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="/logout">
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Tagihan - {{ $tagihan->id_tagihan }}</h1>
                        <a href="{{ route('dashboard.datatagihan.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Data Tagihan
                        </a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Form Edit Tagihan -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-edit"></i> Form Edit Tagihan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan dalam pengisian form:
                                            <ul class="mt-2 mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form action="{{ route('dashboard.datatagihan.update', $tagihan->id_tagihan) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Pilih Pelanggan -->
                                        <div class="form-group">
                                            <label for="user_id" class="form-label">
                                                <i class="fas fa-user"></i> Pelanggan <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required onchange="updatePaketOptions()">
                                                <option value="">Pilih Pelanggan</option>
                                                @if(isset($users))
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" 
                                                                data-paket="{{ $user->paket_wifi_id ?? '' }}"
                                                                {{ old('user_id', $tagihan->user_id) == $user->id ? 'selected' : '' }}>
                                                            {{ $user->nama }} - {{ $user->username }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Pilih Paket WiFi -->
                                        <div class="form-group">
                                            <label for="paket_wifi_id" class="form-label">
                                                <i class="fas fa-wifi"></i> Paket WiFi <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('paket_wifi_id') is-invalid @enderror" id="paket_wifi_id" name="paket_wifi_id" required onchange="updateHarga()">
                                                <option value="">Pilih Paket WiFi</option>
                                                @if(isset($paketWifi) && $paketWifi->count() > 0)
                                                    @foreach($paketWifi as $paket)
                                                        <option value="{{ $paket->id }}" 
                                                                data-harga="{{ $paket->harga }}"
                                                                {{ old('paket_wifi_id', $tagihan->paket_wifi_id) == $paket->id ? 'selected' : '' }}>
                                                            {{ $paket->nama_paket }} - {{ $paket->kecepatan }} (Rp {{ number_format($paket->harga, 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('paket_wifi_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Periode Tagihan -->
                                        <div class="form-group">
                                            <label for="periode_tagihan" class="form-label">
                                                <i class="fas fa-calendar"></i> Periode Tagihan <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('periode_tagihan') is-invalid @enderror" 
                                                   id="periode_tagihan" 
                                                   name="periode_tagihan" 
                                                   value="{{ old('periode_tagihan', $tagihan->periode_tagihan ? date('Y-m-d', strtotime($tagihan->periode_tagihan)) : '') }}" 
                                                   required>
                                            @error('periode_tagihan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Pilih tanggal periode tagihan (biasanya tanggal 1 setiap bulan)</small>
                                        </div>

                                        <!-- Jumlah Tagihan -->
                                        <div class="form-group">
                                            <label for="jumlah_tagihan" class="form-label">
                                                <i class="fas fa-dollar-sign"></i> Jumlah Tagihan <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control @error('jumlah_tagihan') is-invalid @enderror" 
                                                       id="jumlah_tagihan" 
                                                       name="jumlah_tagihan" 
                                                       value="{{ old('jumlah_tagihan', $tagihan->jumlah_tagihan) }}" 
                                                       min="0" 
                                                       step="1000" 
                                                       required>
                                                @error('jumlah_tagihan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Jumlah akan otomatis terisi sesuai paket yang dipilih</small>
                                        </div>

                                        <!-- Jatuh Tempo -->
                                        <div class="form-group">
                                            <label for="jatuh_tempo" class="form-label">
                                                <i class="fas fa-clock"></i> Jatuh Tempo <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('jatuh_tempo') is-invalid @enderror" 
                                                   id="jatuh_tempo" 
                                                   name="jatuh_tempo" 
                                                   value="{{ old('jatuh_tempo', date('Y-m-d', strtotime($tagihan->jatuh_tempo))) }}" 
                                                   required>
                                            @error('jatuh_tempo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Tanggal maksimal pembayaran tagihan</small>
                                        </div>

                                        <!-- Denda (Opsional) -->
                                        <div class="form-group">
                                            <label for="denda" class="form-label">
                                                <i class="fas fa-exclamation-triangle"></i> Denda (Opsional)
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control @error('denda') is-invalid @enderror" 
                                                       id="denda" 
                                                       name="denda" 
                                                       value="{{ old('denda', $tagihan->denda ?? 0) }}" 
                                                       min="0" 
                                                       step="1000">
                                                @error('denda')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Denda keterlambatan pembayaran (jika ada)</small>
                                        </div>

                                        <!-- Status Tagihan -->
                                        <div class="form-group">
                                            <label for="status_tagihan_id" class="form-label">
                                                <i class="fas fa-info-circle"></i> Status Tagihan <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('status_tagihan_id') is-invalid @enderror" id="status_tagihan_id" name="status_tagihan_id" required>
                                                @if(isset($statusTagihan))
                                                    @foreach($statusTagihan as $status)
                                                        <option value="{{ $status->id }}" 
                                                                {{ old('status_tagihan_id', $tagihan->status_tagihan_id) == $status->id ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('status_tagihan_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Catatan (Opsional) -->
                                        <div class="form-group">
                                            <label for="catatan" class="form-label">
                                                <i class="fas fa-sticky-note"></i> Catatan (Opsional)
                                            </label>
                                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                                      id="catatan" 
                                                      name="catatan" 
                                                      rows="3" 
                                                      placeholder="Catatan tambahan untuk tagihan ini...">{{ old('catatan', $tagihan->catatan ?? '') }}</textarea>
                                            @error('catatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tombol Aksi -->
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('dashboard.datatagihan.index') }}" class="btn btn-secondary">
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
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">
                                        <i class="fas fa-info-circle"></i> Informasi Tagihan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>ID Tagihan:</strong> {{ $tagihan->id_tagihan }}</p>
                                            <p><strong>Dibuat:</strong> {{ date('d/m/Y H:i', strtotime($tagihan->created_at)) }}</p>
                                            <p><strong>Terakhir Update:</strong> {{ date('d/m/Y H:i', strtotime($tagihan->updated_at)) }}</p>
                                            @if($tagihan->is_auto_generated ?? false)
                                                <p><span class="badge badge-info"><i class="fas fa-robot"></i> Auto Generated</span></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tips Panel -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-warning">
                                        <i class="fas fa-lightbulb"></i> Tips Pengisian
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Pastikan pelanggan sudah terdaftar</li>
                                        <li><i class="fas fa-check text-success"></i> Pilih paket sesuai langganan pelanggan</li>
                                        <li><i class="fas fa-check text-success"></i> Jatuh tempo biasanya 15-30 hari dari periode</li>
                                        <li><i class="fas fa-check text-success"></i> Denda hanya diisi jika pembayaran terlambat</li>
                                    </ul>
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

    <script>
        // Function to update paket options based on selected user
        function updatePaketOptions() {
            const userSelect = document.getElementById('user_id');
            const paketSelect = document.getElementById('paket_wifi_id');
            const selectedOption = userSelect.options[userSelect.selectedIndex];
            
            if (selectedOption.dataset.paket) {
                paketSelect.value = selectedOption.dataset.paket;
                updateHarga();
            }
        }

        // Function to update harga based on selected paket
        function updateHarga() {
            const paketSelect = document.getElementById('paket_wifi_id');
            const jumlahInput = document.getElementById('jumlah_tagihan');
            const selectedOption = paketSelect.options[paketSelect.selectedIndex];
            
            if (selectedOption.dataset.harga) {
                jumlahInput.value = selectedOption.dataset.harga;
            }
        }

        // Auto calculate jatuh tempo based on periode
        document.getElementById('periode_tagihan').addEventListener('change', function() {
            const periodeDate = new Date(this.value);
            if (periodeDate) {
                // Add 30 days for jatuh tempo
                const jatuhTempo = new Date(periodeDate);
                jatuhTempo.setDate(jatuhTempo.getDate() + 30);
                
                // Format to YYYY-MM-DD
                const formattedDate = jatuhTempo.toISOString().split('T')[0];
                document.getElementById('jatuh_tempo').value = formattedDate;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePaketOptions();
        });
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit Pengajuan WiFi</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <div class="container-fluid">
            <!-- Card for Pengajuan WiFi Edit Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Pengajuan WiFi</h6>
                    <a href="{{ route('dashboard.pengajuan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <!-- Alert untuk menampilkan pesan sukses/error -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.pengajuan.update', $pengajuan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- ID Pengajuan (Read Only) -->
                                <div class="form-group mb-3">
                                    <label for="id" class="form-label">ID Pengajuan</label>
                                    <input type="text" class="form-control" id="id" value="{{ $pengajuan->id }}" readonly>
                                </div>
                                
                                <!-- Nama Pelanggan -->
                                <div class="form-group mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                        id="nama_lengkap" name="nama_lengkap" 
                                        value="{{ old('nama_lengkap', $pengajuan->nama_lengkap ?? $pengajuan->user->nama ?? '') }}" required>
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Alamat Pengajuan -->
                                <div class="form-group mb-3">
                                    <label for="alamat_lengkap" class="form-label">Alamat Pengajuan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" 
                                        id="alamat_lengkap" name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap', $pengajuan->alamat_lengkap ?? $pengajuan->user->alamat ?? '') }}</textarea>
                                    @error('alamat_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- No Telepon -->
                                <div class="form-group mb-3">
                                    <label for="no_telepon" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                        id="no_telepon" name="no_telepon" 
                                        value="{{ old('no_telepon', $pengajuan->no_telepon ?? $pengajuan->user->no_hp ?? '') }}" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <!-- Email -->
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email" 
                                        value="{{ old('email', $pengajuan->email ?? $pengajuan->user->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Paket WiFi -->
                                <div class="form-group mb-3">
                                    <label for="paket_wifi_id" class="form-label">Paket WiFi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('paket_wifi_id') is-invalid @enderror" 
                                        id="paket_wifi_id" name="paket_wifi_id" required>
                                        <option value="">Pilih Paket WiFi</option>
                                        @if(isset($paketWifi) && $paketWifi->count() > 0)
                                            @foreach ($paketWifi as $paket)
                                                <option value="{{ $paket->id }}" 
                                                    {{ old('paket_wifi_id', $pengajuan->paket_wifi_id) == $paket->id ? 'selected' : '' }}>
                                                    {{ $paket->nama_paket }} - {{ isset($paket->harga) ? number_format($paket->harga, 0, ',', '.') : '' }}/bulan
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">Tidak ada paket tersedia</option>
                                        @endif
                                    </select>
                                    @error('paket_wifi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Status Pengajuan -->
                                <div class="form-group mb-3">
                                    <label for="status_pengajuan_id" class="form-label">Status Pengajuan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status_pengajuan_id') is-invalid @enderror" 
                                        id="status_pengajuan_id" name="status_pengajuan_id" required>
                                        @if(isset($statusPengajuan) && $statusPengajuan->count() > 0)
                                            @foreach ($statusPengajuan as $status)
                                                <option value="{{ $status->id }}" 
                                                    {{ old('status_pengajuan_id', $pengajuan->status_pengajuan_id) == $status->id ? 'selected' : '' }}>
                                                    {{ $status->status }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">Tidak ada status tersedia</option>
                                        @endif
                                    </select>
                                    @error('status_pengajuan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Tanggal Pengajuan (Read Only) -->
                                <div class="form-group mb-3">
                                    <label for="created_at" class="form-label">Tanggal Pengajuan</label>
                                    <input type="text" class="form-control" id="created_at" 
                                        value="{{ $pengajuan->created_at->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Catatan Tambahan (Opsional) -->
                        <div class="form-group mb-3">
                            <label for="catatan" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                id="catatan" name="catatan" rows="3" 
                                placeholder="Masukkan catatan jika diperlukan...">{{ old('catatan', $pengajuan->catatan ?? '') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="form-group text-end">
                            <a href="{{ route('dashboard.pengajuan.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Informasi Tambahan -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Informasi Pengajuan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dibuat pada:</strong> {{ $pengajuan->created_at->format('d F Y, H:i') }}</p>
                            <p><strong>Terakhir diupdate:</strong> {{ $pengajuan->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status saat ini:</strong> 
                                <span class="badge bg-{{ isset($pengajuan->statusPengajuan) && $pengajuan->statusPengajuan->status == 'Disetujui' ? 'success' : (isset($pengajuan->statusPengajuan) && $pengajuan->statusPengajuan->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                    {{ $pengajuan->statusPengajuan->status ?? 'N/A' }}
                                </span>
                            </p>
                            @if($pengajuan->user)
                                <p><strong>User terkait:</strong> {{ $pengajuan->user->nama ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>
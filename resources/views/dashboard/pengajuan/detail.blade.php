<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan WiFi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>

    <!-- Page Content -->
    <div class="container mt-5">
        <!-- Back Button -->
        <a href="{{ route('dashboard.pengajuan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengajuan
        </a>

        <!-- Card for Pengajuan Details -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Pengajuan WiFi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID Pengajuan:</strong> {{ $pengajuan->id }}</p>
                        <p><strong>Nama Pelanggan:</strong> {{ $pengajuan->nama_lengkap ?? $pengajuan->user->nama ?? 'N/A' }}</p>
                        <p><strong>Alamat Pengajuan:</strong> {{ $pengajuan->alamat_lengkap ?? $pengajuan->user->alamat ?? 'N/A' }}</p>
                        <p><strong>No Telepon:</strong> {{ $pengajuan->no_telepon ?? $pengajuan->user->no_hp ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $pengajuan->email ?? $pengajuan->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Paket WiFi:</strong> {{ $pengajuan->paketWifi->nama_paket ?? 'N/A' }}</p>
                        <p><strong>Status Pengajuan:</strong> {{ $pengajuan->statusPengajuan->status ?? 'N/A' }}</p>
                        <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->created_at->format('d F Y, H:i') }}</p>
                        <p><strong>Terakhir Diupdate:</strong> {{ $pengajuan->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                <hr>

                <!-- Catatan Tambahan -->
                <div class="form-group">
                    <label for="catatan">Catatan Tambahan:</label>
                    <p>{{ $pengajuan->catatan ?? 'Tidak ada catatan tambahan.' }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-3">
                    <a href="{{ route('dashboard.pengajuan.edit', $pengajuan->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit Pengajuan
                    </a>

                    <!-- Delete Form -->
                    <form action="{{ route('dashboard.pengajuan.destroy', $pengajuan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengajuan ini?')">
                            <i class="fas fa-trash"></i> Hapus Pengajuan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaketWifi;
use App\Models\Tagihan;
use App\Models\Pengajuan;
use App\Models\Pengaduan;
use App\Models\StatusPengajuan;
use App\Models\StatusPengaduan;
use App\Models\Keluhan;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    protected $notificationController;

    public function __construct()
    {
        // Terapkan middleware pada semua metode di controller ini
        $this->middleware(['auth', 'role:superadmin']);
        $this->notificationController = new NotificationController();
    }

    /**
     * Menampilkan dashboard utama superadmin
     */
    public function dashboard()
    {
        // Statistik pengguna dan paket
        $totalUsers = User::count();
        $totalPaket = PaketWifi::count();

        // Statistik tagihan
        $belumDibayar = Tagihan::where('status_tagihan_id', 1)->count();
        $lunas = Tagihan::where('status_tagihan_id', 3)->count();

        // Statistik pengajuan
        $totalPengajuan = Pengajuan::count();
        $pengajuanSelesai = Pengajuan::whereHas('statusPengajuan', function($query) {
            $query->where('status', 'Selesai');
        })->count();
        $pengajuanPending = Pengajuan::whereHas('statusPengajuan', function($query) {
            $query->where('status', 'Menunggu Persetujuan');
        })->count();

        // Statistik pengaduan
        $totalPengaduan = Pengaduan::count();
        $pengaduanSelesai = Pengaduan::whereHas('statusPengaduan', function($query) {
            $query->where('status', 'Selesai');
        })->count();
        $pengaduanPending = Pengaduan::whereHas('statusPengaduan', function($query) {
            $query->where('status', 'Menunggu Tindakan');
        })->count();

        // Statistik keuangan
        $pendapatanTahunIni = $this->hitungPendapatanTahunIni();
        $totalTunggakan = $this->hitungTotalTunggakan();
        $rataRataBulanan = $this->hitungRataRataBulanan();

        // Data untuk grafik
        $grafikPendapatan = $this->getGrafikPendapatan();
        $statusPembayaran = $this->getStatusPembayaran();

        // Data terbaru
        $pengajuanTerbaru = $this->getPengajuanTerbaru();
        $pengaduanTerbaru = $this->getPengaduanTerbaru();

        return view('superadmin.dashboard', compact(
            'totalUsers', 'totalPaket', 'belumDibayar', 'lunas',
            'totalPengajuan', 'pengajuanSelesai', 'pengajuanPending',
            'totalPengaduan', 'pengaduanSelesai', 'pengaduanPending',
            'pendapatanTahunIni', 'totalTunggakan', 'rataRataBulanan',
            'grafikPendapatan', 'statusPembayaran', 'pengajuanTerbaru', 'pengaduanTerbaru'
        ));
    }

    /**
     * ========================================
     * MANAJEMEN PAKET WIFI
     * ========================================
     */

    /**
     * Menampilkan daftar paket wifi
     */
    public function showPaketWifiSuperAdmin()
    {
        $paketWifi = PaketWifi::all();
        return view('superadmin.datapaket.index', compact('paketWifi'));
    }

    /**
     * Menyimpan paket wifi baru
     */
    public function storePaketWifi(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:20',
            'harga_bulanan' => 'required|numeric',
            'deskripsi_paket' => 'required|string',
        ]);

        PaketWifi::create($request->all());

        return redirect()->route('superadmin.paketwifi.index')
                        ->with('success', 'Paket Wi-Fi berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit paket wifi
     */
    public function editPaketWifi($id)
    {
        $paketWifi = PaketWifi::findOrFail($id);
        return view('superadmin.datapaket.edit', compact('paketWifi'));
    }

    /**
     * Memperbarui paket wifi
     */
    public function updatePaketWifi(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:20',
            'harga_bulanan' => 'required|numeric',
            'deskripsi_paket' => 'required|string',
        ]);

        $paketWifi = PaketWifi::findOrFail($id);
        $paketWifi->update($request->all());

        return redirect()->route('superadmin.datapaket.index')
                        ->with('success', 'Paket Wi-Fi berhasil diperbarui!');
    }

    /**
     * Menghapus paket wifi
     */
    public function deletePaketWifi($id)
    {
        $paketWifi = PaketWifi::findOrFail($id);
        $paketWifi->delete();

        return redirect()->route('superadmin.datapaket.index')
                        ->with('success', 'Paket Wi-Fi berhasil dihapus!');
    }

    /**
     * ========================================
     * MANAJEMEN PELANGGAN
     * ========================================
     */

    /**
     * Menampilkan daftar pelanggan
     */
    public function index()
    {
        $pelanggan = User::whereNotIn('role', ['admin', 'superadmin', 'teknisi'])->get();
        $belumDibayar = Tagihan::where('status_tagihan_id', 1)->count();
        $lunas = Tagihan::where('status_tagihan_id', 3)->count();

        return view('superadmin.pelanggan.index', compact('pelanggan', 'belumDibayar', 'lunas'));
    }

    /**
     * ========================================
     * MANAJEMEN PENGAJUAN
     * ========================================
     */

    /**
     * Menampilkan daftar pengajuan
     */
    public function pengajuan()
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        $statusPengajuan = StatusPengajuan::all();

        return view('superadmin.pengajuan.index', compact('pengajuan', 'statusPengajuan'));
    }

    /**
     * Menampilkan detail pengajuan
     */
    public function showPengajuan($id)
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan', 'tagihan'])
                              ->findOrFail($id);

        $statusPengajuan = StatusPengajuan::all();

        return view('superadmin.pengajuan.show', compact('pengajuan', 'statusPengajuan'));
    }

    /**
     * Memperbarui status pengajuan
     */
    public function updatePengajuan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id_status_pengajuan',
            'catatan_admin' => 'nullable|string|max:1000',
        ], [
            'status_pengajuan_id.required' => 'Status pengajuan wajib dipilih.',
            'status_pengajuan_id.exists' => 'Status pengajuan yang dipilih tidak valid.',
            'catatan_admin.max' => 'Catatan admin maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengajuan = Pengajuan::findOrFail($id);
            $oldStatusId = $pengajuan->status_pengajuan_id;

            $pengajuan->update([
                'status_pengajuan_id' => $request->status_pengajuan_id,
                'catatan_admin' => $request->catatan_admin,
                'admin_id' => Auth::id(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi jika status berubah
            if ($oldStatusId != $request->status_pengajuan_id) {
                $this->kirimNotifikasiPengajuan($pengajuan);
            }

            return redirect()->route('superadmin.pengajuan.show', $id)
                           ->with('success', 'Status pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengajuan: ' . $e->getMessage(), [
                'pengajuan_id' => $id,
                'superadmin_id' => Auth::id(),
            ]);

            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat memperbarui status pengajuan.');
        }
    }

    /**
     * ========================================
     * MANAJEMEN PENGADUAN
     * ========================================
     */

    /**
     * Menampilkan daftar pengaduan
     */
    public function pengaduan()
    {
        $pengaduan = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        $statusPengaduan = StatusPengaduan::all();

        return view('superadmin.pengaduan.index', compact('pengaduan', 'statusPengaduan'));
    }

    /**
     * Menampilkan detail pengaduan
     */
    public function showPengaduan($id)
    {
        $pengaduan = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                             ->findOrFail($id);

        $statusPengaduan = StatusPengaduan::all();

        return view('superadmin.pengaduan.show', compact('pengaduan', 'statusPengaduan'));
    }

    /**
     * Memperbarui status pengaduan
     */
    public function updatePengaduan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pengaduan_id' => 'required|exists:status_pengaduan,id_status_pengaduan',
            'catatan_admin' => 'nullable|string|max:1000',
        ], [
            'status_pengaduan_id.required' => 'Status pengaduan wajib dipilih.',
            'status_pengaduan_id.exists' => 'Status pengaduan yang dipilih tidak valid.',
            'catatan_admin.max' => 'Catatan admin maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengaduan = Pengaduan::findOrFail($id);
            $oldStatusId = $pengaduan->status_id_status;

            $pengaduan->update([
                'status_id_status' => $request->status_pengaduan_id,
                'catatan_admin' => $request->catatan_admin,
                'admin_id' => Auth::id(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi jika status berubah
            if ($oldStatusId != $request->status_pengaduan_id) {
                $this->kirimNotifikasiPengaduan($pengaduan);
            }

            return redirect()->route('superadmin.pengaduan.show', $id)
                           ->with('success', 'Status pengaduan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengaduan: ' . $e->getMessage(), [
                'pengaduan_id' => $id,
                'superadmin_id' => Auth::id(),
            ]);

            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat memperbarui status pengaduan.');
        }
    }

    /**
     * ========================================
     * HELPER METHODS - STATISTIK KEUANGAN
     * ========================================
     */

    /**
     * Menghitung pendapatan tahun ini
     */
    private function hitungPendapatanTahunIni()
    {
        return Tagihan::where('status_tagihan_id', 3)
                     ->whereYear('jatuh_tempo', Carbon::now()->year)
                     ->sum('jumlah_tagihan');
    }

    /**
     * Menghitung total tunggakan
     */
    private function hitungTotalTunggakan()
    {
        return Tagihan::where('status_tagihan_id', 1)
                     ->sum('jumlah_tagihan');
    }

    /**
     * Menghitung rata-rata pendapatan bulanan
     */
    private function hitungRataRataBulanan()
    {
        $totalPendapatan = Tagihan::where('status_tagihan_id', 3)
                                 ->where('jatuh_tempo', '>=', Carbon::now()->subMonths(12))
                                 ->sum('jumlah_tagihan');
        
        return $totalPendapatan / 12;
    }

    /**
     * Mendapatkan data grafik pendapatan
     */
    private function getGrafikPendapatan()
    {
        return Tagihan::where('status_tagihan_id', 3)
                     ->where('jatuh_tempo', '>=', Carbon::now()->subMonths(12))
                     ->selectRaw('SUM(jumlah_tagihan) as total, MONTH(jatuh_tempo) as month')
                     ->groupBy('month')
                     ->orderBy('month', 'asc')
                     ->get();
    }

    /**
     * Mendapatkan data status pembayaran
     */
    private function getStatusPembayaran()
    {
        return [
            'lunas' => Tagihan::where('status_tagihan_id', 3)->sum('jumlah_tagihan'),
            'belum_dibayar' => Tagihan::where('status_tagihan_id', 1)->sum('jumlah_tagihan')
        ];
    }

    /**
     * Mendapatkan pengajuan terbaru
     */
    private function getPengajuanTerbaru()
    {
        return Pengajuan::with(['user', 'paketWifi', 'statusPengajuan'])
                       ->orderBy('created_at', 'desc')
                       ->take(5)
                       ->get();
    }

    /**
     * Mendapatkan pengaduan terbaru
     */
    private function getPengaduanTerbaru()
    {
        return Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                       ->orderBy('created_at', 'desc')
                       ->take(5)
                       ->get();
    }

    /**
     * ========================================
     * HELPER METHODS - NOTIFIKASI
     * ========================================
     */

    /**
     * Mengirim notifikasi perubahan status pengajuan
     */
    private function kirimNotifikasiPengajuan($pengajuan)
    {
        try {
            $pengajuan->load(['user', 'paketWifi', 'statusPengajuan']);
            
            $user = $pengajuan->user;
            $status = $pengajuan->statusPengajuan?->status ?? 'Tidak Diketahui';
            $superAdminName = Auth::user()->name ?? 'Super Admin';
            $paketName = $pengajuan->paketWifi?->nama_paket ?? 'Paket WiFi';

            // Notifikasi ke pelanggan
            if ($user) {
                $this->buatNotifikasiPelanggan($user, $status, $paketName, $superAdminName, $pengajuan, 'pengajuan');
                $this->kirimEmailWhatsApp($user, $status, $paketName, $superAdminName, 'pengajuan');
            }

            // Notifikasi ke admin dan teknisi
            $this->kirimNotifikasiKeStaf($pengajuan, $status, $paketName, $superAdminName, 'pengajuan');

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi pengajuan: ' . $e->getMessage(), [
                'pengajuan_id' => $pengajuan->id,
                'superadmin_id' => Auth::id()
            ]);
        }
    }

    /**
     * Mengirim notifikasi perubahan status pengaduan
     */
    private function kirimNotifikasiPengaduan($pengaduan)
    {
        try {
            $pengaduan->load(['user', 'keluhan', 'statusPengaduan']);
            
            $user = $pengaduan->user;
            $status = $pengaduan->statusPengaduan?->status ?? 'Tidak Diketahui';
            $superAdminName = Auth::user()->name ?? 'Super Admin';
            $keluhanName = $pengaduan->keluhan?->nama_keluhan ?? 'Keluhan';

            // Notifikasi ke pelanggan
            if ($user) {
                $this->buatNotifikasiPelanggan($user, $status, $keluhanName, $superAdminName, $pengaduan, 'pengaduan');
                $this->kirimEmailWhatsApp($user, $status, $keluhanName, $superAdminName, 'pengaduan');
            }

            // Notifikasi ke admin dan teknisi
            $this->kirimNotifikasiKeStaf($pengaduan, $status, $keluhanName, $superAdminName, 'pengaduan');

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi pengaduan: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
                'superadmin_id' => Auth::id()
            ]);
        }
    }

    /**
     * Membuat notifikasi untuk pelanggan
     */
    private function buatNotifikasiPelanggan($user, $status, $itemName, $superAdminName, $item, $type)
    {
        $isCompleted = $status === 'Selesai';
        $messageType = $type === 'pengajuan' ? 'pengajuan' : 'pengaduan';
        
        $title = $isCompleted ? 
            ucfirst($messageType) . ' Diselesaikan oleh Super Admin' : 
            'Status ' . ucfirst($messageType) . ' Diperbarui';

        $message = $isCompleted ?
            ucfirst($messageType) . " WiFi Anda terkait {$itemName} telah diselesaikan oleh super admin {$superAdminName}" :
            "Status {$messageType} WiFi Anda terkait {$itemName} telah diperbarui menjadi: {$status}";

        Notification::create([
            'user_id' => $user->id_user,
            'title' => $title,
            'message' => $message,
            'type' => $type . '_status_change_by_superadmin',
            'data' => json_encode([
                $type . '_id' => $item->id,
                'new_status' => $status,
                'updated_by' => 'superadmin',
                'superadmin_name' => $superAdminName,
                'item_name' => $itemName,
                'is_completed' => $isCompleted
            ]),
            'is_read' => false,
            'created_at' => now()
        ]);
    }

    /**
     * Mengirim notifikasi ke admin dan teknisi
     */
    private function kirimNotifikasiKeStaf($item, $status, $itemName, $superAdminName, $type)
    {
        $userName = $item->user?->nama ?? 'User';
        $roles = ['admin', 'teknisi'];

        foreach ($roles as $role) {
            $staffUsers = User::where('role', $role)->get();
            
            foreach ($staffUsers as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'title' => 'Status ' . ucfirst($type) . ' Diperbarui oleh Super Admin',
                    'message' => "Super Admin {$superAdminName} telah memperbarui status {$type} {$userName} menjadi: {$status}",
                    'type' => $type . '_updated_by_superadmin',
                    'data' => json_encode([
                        $type . '_id' => $item->id,
                        'user_name' => $userName,
                        'superadmin_name' => $superAdminName,
                        'new_status' => $status,
                        'item_name' => $itemName
                    ]),
                    'is_read' => false,
                    'created_at' => now()
                ]);

                // Kirim email ke staff
                if ($staff->email) {
                    $emailMessage = "Super Admin {$superAdminName} telah memperbarui status {$type} WiFi.\n\n" .
                                  "Detail:\n" .
                                  "- Pelanggan: {$userName}\n" .
                                  "- Item: {$itemName}\n" .
                                  "- Status Baru: {$status}\n" .
                                  "- Super Admin: {$superAdminName}\n" .
                                  "- Tanggal: " . now()->format('d-m-Y H:i') . "\n\n" .
                                  "Silakan cek dashboard untuk detail lebih lanjut.";
                    
                    $this->notificationController->sendEmailNotification(
                        $staff->email,
                        'Status ' . ucfirst($type) . ' Diperbarui oleh Super Admin',
                        $emailMessage
                    );
                }
            }
        }
    }

    /**
     * Mengirim email dan WhatsApp ke pelanggan
     */
    private function kirimEmailWhatsApp($user, $status, $itemName, $superAdminName, $type)
    {
        $isCompleted = $status === 'Selesai';
        
        // Kirim email
        if ($user->email) {
            $emailMessage = $this->buatPesanEmail($status, $itemName, $superAdminName, $isCompleted, $type);
            $emailTitle = $isCompleted ? 
                ucfirst($type) . ' WiFi Diselesaikan' : 
                'Status ' . ucfirst($type) . ' WiFi Diperbarui';
            
            $this->notificationController->sendEmailNotification($user->email, $emailTitle, $emailMessage);
        }

        // Kirim WhatsApp
        if ($user->no_hp) {
            $whatsappMessage = $this->buatPesanWhatsApp($status, $itemName, $superAdminName, $isCompleted, $type);
            $this->notificationController->sendWhatsAppNotification($user->no_hp, $whatsappMessage);
        }
    }

    /**
     * Membuat pesan email berdasarkan status
     */
    private function buatPesanEmail($status, $itemName, $adminName, $isCompleted, $type)
    {
        $typeText = $type === 'pengajuan' ? 'pengajuan' : 'pengaduan';
        
        if ($isCompleted) {
            return "✅ {$typeText} WiFi Anda terkait {$itemName} telah diselesaikan oleh super admin {$adminName}.\n\nTerima kasih atas kesabaran Anda!";
        }

        switch ($status) {
            case 'Disetujui':
                return "🎉 Selamat! Pengajuan WiFi Anda untuk paket {$itemName} telah DISETUJUI oleh super admin {$adminName}. Tim teknis akan segera menghubungi Anda untuk proses instalasi.";
            case 'Ditolak':
                return "Mohon maaf, pengajuan WiFi Anda untuk paket {$itemName} tidak dapat disetujui oleh super admin {$adminName}. Silakan hubungi customer service untuk informasi lebih lanjut.";
            case 'Dalam Proses':
                return "Pengajuan WiFi Anda untuk paket {$itemName} sedang dalam proses review oleh super admin {$adminName}. Harap tunggu konfirmasi selanjutnya.";
            default:
                return "Status {$typeText} WiFi Anda terkait {$itemName} telah diperbarui oleh super admin {$adminName} menjadi: {$status}";
        }
    }

    /**
     * Membuat pesan WhatsApp berdasarkan status
     */
    private function buatPesanWhatsApp($status, $itemName, $adminName, $isCompleted, $type)
    {
        $typeText = $type === 'pengajuan' ? 'PENGAJUAN' : 'PENGADUAN';
        
        if ($isCompleted) {
            return "✅ *{$typeText} SELESAI!*\n\n" . 
                   ucfirst($type) . " WiFi Anda terkait *{$itemName}* telah diselesaikan oleh super admin *{$adminName}*.\n\n" .
                   "Terima kasih atas kesabaran Anda! 🙏";
        }

        switch ($status) {
            case 'Disetujui':
                return "🎉 *PENGAJUAN DISETUJUI!*\n\nSelamat! Pengajuan WiFi Anda untuk paket *{$itemName}* telah disetujui oleh super admin *{$adminName}*.\n\n📞 Tim teknis akan segera menghubungi Anda untuk jadwal instalasi.\n\nTerima kasih! 🙏";
            case 'Ditolak':
                return "❌ *PENGAJUAN DITOLAK*\n\nMohon maaf, pengajuan WiFi Anda untuk paket *{$itemName}* tidak dapat disetujui oleh super admin *{$adminName}*.\n\nSilakan hubungi customer service untuk informasi lebih lanjut.\n\nTerima kasih!";
            case 'Dalam Proses':
                return "🔄 *PENGAJUAN DALAM PROSES*\n\nPengajuan WiFi Anda untuk paket *{$itemName}* sedang dalam proses review oleh super admin *{$adminName}*.\n\nHarap tunggu konfirmasi selanjutnya.\n\nTerima kasih atas kesabaran Anda!";
            default:
                return "📌 *{$typeText} DIPERBARUI*\n\nStatus " . strtolower($type) . " WiFi Anda terkait *{$itemName}* telah diperbarui oleh super admin *{$adminName}* menjadi: *{$status}*.\n\nTerima kasih atas perhatian Anda!";
        }
    }
}
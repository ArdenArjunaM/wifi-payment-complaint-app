<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\PaketWifi;
use App\Models\StatusPengajuan;
use App\Models\Tagihan;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    protected $notificationController;

    public function __construct()
    {
        $this->notificationController = new NotificationController();
    }

    /**
     * Menampilkan daftar pengajuan untuk dashboard admin
     */
    public function index()
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        $statusPengajuan = StatusPengajuan::all();

        return view('dashboard.pengajuan.index', compact('pengajuan', 'statusPengajuan'));
    }

    /**
     * Menampilkan daftar pengajuan untuk super admin
     */
    public function pengajuanSuperAdmin()
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        $statusPengajuan = StatusPengajuan::all();

        return view('superadmin.pengajuan.index', compact('pengajuan', 'statusPengajuan'));
    }

    /**
     * Menampilkan daftar pengajuan untuk teknisi
     */
    public function indexTeknisi()
    {
        $pengajuan = Pengajuan::whereHas('user', function($query) {
            $query->where('role_id', 4); // 4 adalah id untuk role 'teknisi'
        })->get();

        return view('teknisi.pengajuan.index', compact('pengajuan'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru (Admin)
     */
    public function create()
    {
        $paket_wifi = PaketWifi::all();
        $status_pengajuan = StatusPengajuan::all();
        $rrs = User::all();

        return view('dashboard.pengajuan.create', compact('paket_wifi', 'status_pengajuan', 'users'));
    }

    /**
     * Menyimpan pengajuan baru dari user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi'
        ]);

        try {
            // Cek apakah user sudah memiliki pengajuan yang sedang diproses
            $existingPengajuan = Pengajuan::where('users_id_user', Auth::id())
                                          ->whereIn('status_pengajuan_id', [4, 5]) // Status Menunggu atau Selesai
                                          ->exists();

            if ($existingPengajuan) {
                return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses atau selesai.');
            }

            // Buat pengajuan baru
            $pengajuan = Pengajuan::create([
                'users_id_user' => Auth::id(),
                'paket_wifi_id_paket_wifi' => $validated['paket_wifi_id_paket_wifi'],
                'status_pengajuan_id' => 4, // Status 'Menunggu'
            ]);

            // Kirim notifikasi ke admin
            $this->kirimNotifikasiPengajuanBaruKeAdmin($pengajuan);

            return redirect()->route('user.paketwifi')
                             ->with('success', 'Pengajuan berhasil dikirim dan sedang diproses.');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengajuan: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan. Silakan coba lagi.')
                             ->withInput();
        }
    }

    /**
     * Menyimpan pengajuan dari dashboard admin
     */
    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_id_user' => 'required|exists:users,id_user',
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi',
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengajuan = Pengajuan::create([
                'users_id_user' => $request->users_id_user,
                'paket_wifi_id_paket_wifi' => $request->paket_wifi_id_paket_wifi,
                'status_pengajuan_id' => $request->status_pengajuan_id,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'alamat_lengkap' => $request->alamat_lengkap,
            ]);

            return redirect()->route('dashboard.pengajuan.index')
                             ->with('success', 'Pengajuan berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Menyimpan pengajuan dari dashboard super admin
     */
    public function storeSuperAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_id_user' => 'required|exists:users,id_user',
            'paket_wifi_id_paket_wifi' => 'required|exists:paket_wifi,id_paket_wifi',
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengajuan = Pengajuan::create([
                'users_id_user' => $request->users_id_user,
                'paket_wifi_id_paket_wifi' => $request->paket_wifi_id_paket_wifi,
                'status_pengajuan_id' => $request->status_pengajuan_id,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'alamat_lengkap' => $request->alamat_lengkap,
            ]);

            return redirect()->route('superadmin.pengajuan.index')
                             ->with('success', 'Pengajuan berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Menampilkan detail pengajuan untuk dashboard admin
     */
    public function show(string $id)
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan', 'tagihan'])
                              ->findOrFail($id);

        return view('dashboard.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Menampilkan detail pengajuan untuk super admin
     */
    public function showSuperAdmin(string $id)
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan', 'tagihan'])
                              ->findOrFail($id);

        return view('superadmin.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Menampilkan form edit pengajuan untuk admin
     */
    public function edit(string $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $paket_wifi = PaketWifi::all();
        $status_pengajuan = StatusPengajuan::all();
        $users = User::all();

        return view('dashboard.pengajuan.edit', compact('pengajuan', 'paket_wifi', 'status_pengajuan', 'users'));
    }

    /**
     * Menampilkan form edit pengajuan untuk super admin
     */
    public function editSuperAdmin(string $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $paket_wifi = PaketWifi::all();
        $status_pengajuan = StatusPengajuan::all();
        $users = User::all();

        return view('superadmin.pengajuan.edit', compact('pengajuan', 'paket_wifi', 'status_pengajuan', 'users'));
    }

    /**
     * Memperbarui pengajuan untuk admin
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id_status_pengajuan',
        ], [
            'status_pengajuan_id.required' => 'Status pengajuan wajib dipilih.',
            'status_pengajuan_id.exists' => 'Status pengajuan yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengajuan = Pengajuan::findOrFail($id);
            $oldStatusId = $pengajuan->status_pengajuan_id;

            // Update status pengajuan
            $pengajuan->update([
                'status_pengajuan_id' => $request->status_pengajuan_id,
            ]);

            // Cari status 'Selesai'
            $statusSelesai = StatusPengajuan::where('status', 'Selesai')->first();

            if (!$statusSelesai) {
                return redirect()->back()->with('error', 'Status "Selesai" tidak ditemukan.');
            }

            // Jika status 'Selesai', buat tagihan otomatis
            if ($request->status_pengajuan_id == $statusSelesai->id) {
                $existingTagihan = Tagihan::where('pengajuan_id', $pengajuan->id)->first();

                if (!$existingTagihan) {
                    TagihanController::createAutoTagihan($pengajuan);
                }
            }

            // Kirim notifikasi jika status berubah
            if ($oldStatusId != $request->status_pengajuan_id) {
                $this->kirimNotifikasiPerubahanStatus($pengajuan);
            }

            return redirect()->route('dashboard.pengajuan.index')
                             ->with('success', 'Pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengajuan: ' . $e->getMessage(), [
                'pengajuan_id' => $id,
                'input' => $request->all()
            ]);

            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui pengajuan untuk super admin
     */
    public function updateSuperAdmin(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id_status_pengajuan',
        ], [
            'status_pengajuan_id.required' => 'Status pengajuan wajib dipilih.',
            'status_pengajuan_id.exists' => 'Status pengajuan yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengajuan = Pengajuan::findOrFail($id);
            $oldStatusId = $pengajuan->status_pengajuan_id;

            // Update status pengajuan
            $pengajuan->update([
                'status_pengajuan_id' => $request->status_pengajuan_id,
            ]);

            // Cari status 'Selesai'
            $statusSelesai = StatusPengajuan::where('status', 'Selesai')->first();

            if (!$statusSelesai) {
                return redirect()->back()->with('error', 'Status "Selesai" tidak ditemukan.');
            }

            // Jika status 'Selesai', buat tagihan otomatis
            if ($request->status_pengajuan_id == $statusSelesai->id) {
                $existingTagihan = Tagihan::where('pengajuan_id', $pengajuan->id)->first();

                if (!$existingTagihan) {
                    TagihanController::createAutoTagihan($pengajuan);
                }
            }

            // Kirim notifikasi jika status berubah
            if ($oldStatusId != $request->status_pengajuan_id) {
                $this->kirimNotifikasiPerubahanStatus($pengajuan);
            }

            return redirect()->route('superadmin.pengajuan.index')
                             ->with('success', 'Pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengajuan: ' . $e->getMessage(), [
                'pengajuan_id' => $id,
                'input' => $request->all()
            ]);

            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pengajuan untuk super admin
     */
    public function updateStatusSuperAdmin(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $request->validate([
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id_status_pengajuan',
        ]);

        $pengajuan->status_pengajuan_id = $request->status_pengajuan_id;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Menghapus pengajuan untuk admin
     */
    public function destroy(string $id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            $pengajuan->delete();

            return redirect()->route('dashboard.pengajuan.index')
                             ->with('success', 'Pengajuan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menghapus pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pengajuan untuk super admin
     */
    public function destroySuperAdmin(string $id)
    {
        try {
            $pengajuan = Pengajuan::findOrFail($id);
            $pengajuan->delete();

            return redirect()->route('superadmin.pengajuan.index')
                             ->with('success', 'Pengajuan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menghapus pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan paket WiFi untuk user
     */
    public function showPackages()
    {
        $paketWifi = PaketWifi::all();
        return view('user.paketwifi', compact('paketWifi'));
    }

    /**
     * Menampilkan pengajuan milik user yang sedang login
     */
    public function mySubmissions()
    {
        $pengajuan = Pengajuan::with(['paketWifi', 'statusPengajuan'])
                              ->where('users_id_user', Auth::id())
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        return view('dashboard.pengajuan.index', compact('pengajuan'));
    }

    /**
     * Mendapatkan notifikasi untuk user yang sedang login
     */
    public function getNotifications()
    {
        return $this->notificationController->getNotifications();
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        return $this->notificationController->markAsRead($id);
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        return $this->notificationController->markAllAsRead();
    }

    /**
     * Relasi untuk status pengajuan
     */
    public function statusPengajuan()
    {
        return $this->belongsTo(StatusPengajuan::class, 'status_pengajuan_id', 'id_status_pengajuan');
    }

    // ==================== FUNGSI PRIVATE ====================

    /**
     * Mengirim notifikasi pengajuan baru ke admin
     */
    private function kirimNotifikasiPengajuanBaruKeAdmin($pengajuan)
    {
        $pengajuan->load(['user', 'paketWifi']);
        
        $namaUser = $this->ambilNamaUser($pengajuan);
        $emailUser = $this->ambilEmailUser($pengajuan);
        $teleponUser = $this->ambilTeleponUser($pengajuan);
        
        // Ambil semua admin (role 1 dan 3)
        $admins = User::whereIn('role', [1, 3])->get();

        foreach ($admins as $admin) {
            // Simpan notifikasi ke database
            Notification::create([
                'user_id' => $admin->id_user,
                'title' => 'Pengajuan WiFi Baru',
                'message' => "Pengajuan WiFi baru dari {$namaUser} untuk paket {$pengajuan->paketWifi->nama_paket}",
                'type' => 'new_submission',
                'data' => json_encode([
                    'pengajuan_id' => $pengajuan->id,
                    'user_name' => $namaUser,
                    'paket_name' => $pengajuan->paketWifi->nama_paket,
                    'user_email' => $emailUser,
                    'user_phone' => $teleponUser
                ]),
                'is_read' => false,
            ]);

            // Kirim email ke admin
            if ($admin->email) {
                $pesanEmail = "Ada pengajuan WiFi baru dari {$namaUser} untuk paket {$pengajuan->paketWifi->nama_paket}.\n\n" .
                             "Detail Pengajuan:\n" .
                             "- Nama: {$namaUser}\n" .
                             "- Email: " . ($emailUser ?? 'Tidak tersedia') . "\n" .
                             "- No HP: " . ($teleponUser ?? 'Tidak tersedia') . "\n" .
                             "- Paket: {$pengajuan->paketWifi->nama_paket}\n" .
                             "- Status: Menunggu\n\n" .
                             "Silakan cek dashboard untuk detail lebih lanjut.";
                
                $this->notificationController->sendEmailNotification(
                    $admin->email, 
                    'Pengajuan WiFi Baru', 
                    $pesanEmail
                );
            }

            // Kirim WhatsApp ke admin
            if ($admin->no_hp) {
                $pesanWA = "🔔 *Pengajuan WiFi Baru*\n\n" .
                          "Pelanggan: {$namaUser}\n" .
                          "Email: " . ($emailUser ?? 'Tidak tersedia') . "\n" .
                          "No HP: " . ($teleponUser ?? 'Tidak tersedia') . "\n" .
                          "Paket: {$pengajuan->paketWifi->nama_paket}\n" .
                          "Status: Menunggu\n\n" .
                          "Silakan cek dashboard untuk detail lebih lanjut.";
                
                $this->notificationController->sendWhatsAppNotification($admin->no_hp, $pesanWA);
            }
        }
    }

    /**
     * Mengirim notifikasi perubahan status ke user
     */
    private function kirimNotifikasiPerubahanStatus($pengajuan)
    {
        $pengajuan->load(['user', 'paketWifi', 'statusPengajuan']);
        
        $user = $pengajuan->user;
        $status = $pengajuan->statusPengajuan->status;
        $namaUser = $this->ambilNamaUser($pengajuan);
        $emailUser = $this->ambilEmailUser($pengajuan);
        $teleponUser = $this->ambilTeleponUser($pengajuan);

        // Simpan notifikasi ke database
        Notification::create([
            'user_id' => $user->id_user,
            'title' => 'Status Pengajuan Berubah',
            'message' => "Status pengajuan WiFi Anda untuk paket {$pengajuan->paketWifi->nama_paket} telah berubah menjadi: {$status}",
            'type' => 'status_change',
            'data' => json_encode([
                'pengajuan_id' => $pengajuan->id,
                'old_status' => $pengajuan->getOriginal('status_pengajuan_id'),
                'new_status' => $pengajuan->status_pengajuan_id,
                'status_name' => $status,
                'user_name' => $namaUser,
                'user_email' => $emailUser,
                'user_phone' => $teleponUser
            ]),
            'is_read' => false,
        ]);

        // Kirim email
        if ($emailUser) {
            $pesanEmail = $this->buatPesanStatusEmail($status, $pengajuan->paketWifi->nama_paket);
            $this->notificationController->sendEmailNotification($emailUser, 'Status Pengajuan WiFi Berubah', $pesanEmail);
        }

        // Kirim WhatsApp
        if ($teleponUser) {
            $pesanWA = $this->buatPesanStatusWhatsApp($status, $pengajuan->paketWifi->nama_paket);
            $this->notificationController->sendWhatsAppNotification($teleponUser, $pesanWA);
        }
    }

    /**
     * Membuat pesan email berdasarkan status
     */
    private function buatPesanStatusEmail($status, $namaPaket)
    {
        switch ($status) {
            case 'Disetujui':
                return "Selamat! Pengajuan WiFi Anda untuk paket {$namaPaket} telah DISETUJUI. Tim teknis kami akan segera menghubungi Anda untuk proses instalasi.";
            case 'Ditolak':
                return "Mohon maaf, pengajuan WiFi Anda untuk paket {$namaPaket} tidak dapat disetujui. Silakan hubungi customer service kami untuk informasi lebih lanjut.";
            case 'Dalam Proses':
                return "Pengajuan WiFi Anda untuk paket {$namaPaket} sedang dalam proses review. Harap tunggu konfirmasi selanjutnya.";
            default:
                return "Status pengajuan WiFi Anda untuk paket {$namaPaket} telah berubah menjadi: {$status}";
        }
    }

    /**
     * Membuat pesan WhatsApp berdasarkan status
     */
    private function buatPesanStatusWhatsApp($status, $namaPaket)
    {
        switch ($status) {
            case 'Disetujui':
                return "🎉 *PENGAJUAN DISETUJUI!*\n\n" .
                       "Selamat! Pengajuan WiFi Anda untuk paket *{$namaPaket}* telah disetujui.\n\n" .
                       "📞 Tim teknis kami akan segera menghubungi Anda untuk jadwal instalasi.\n\n" .
                       "Terima kasih telah mempercayai layanan kami! 🙏";
            case 'Ditolak':
                return "❌ *Pengajuan Tidak Disetujui*\n\n" .
                       "Mohon maaf, pengajuan WiFi Anda untuk paket *{$namaPaket}* tidak dapat disetujui.\n\n" .
                       "📞 Silakan hubungi customer service kami untuk informasi lebih lanjut.\n\n" .
                       "Terima kasih atas pengertiannya.";
            case 'Dalam Proses':
                return "⏳ *Status Update*\n\n" .
                       "Pengajuan WiFi Anda untuk paket *{$namaPaket}* sedang dalam proses review.\n\n" .
                       "✅ Harap tunggu konfirmasi selanjutnya dari tim kami.\n\n" .
                       "Terima kasih atas kesabaran Anda! 🙏";
            default:
                return "🔔 *Status Update*\n\n" .
                       "Status pengajuan WiFi Anda untuk paket *{$namaPaket}* telah berubah menjadi: *{$status}*\n\n" .
                       "Silakan cek dashboard untuk detail lebih lanjut.";
        }
    }

    /**
     * Mengambil email user dari pengajuan
     */
    private function ambilEmailUser($pengajuan)
    {
        return $pengajuan->email ?? $pengajuan->user->email ?? null;
    }

    /**
     * Mengambil nomor telepon user dari pengajuan
     */
    private function ambilTeleponUser($pengajuan)
    {
        return $pengajuan->no_telepon ?? $pengajuan->user->no_hp ?? null;
    }

    /**
     * Mengambil nama user dari pengajuan
     */
    private function ambilNamaUser($pengajuan)
    {
        return $pengajuan->nama_lengkap ?? $pengajuan->user->nama ?? 'User';
    }
}
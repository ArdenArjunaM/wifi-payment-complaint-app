<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\PaketWifi;
use App\Models\StatusPengajuan;
use App\Models\StatusPengaduan;
use App\Models\Keluhan;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TeknisiController extends Controller
{
    protected $notificationController;

    public function __construct()
    {
        $this->notificationController = new NotificationController();
    }

    /**
     * Dashboard teknisi
     */
    public function dashboard()
    {
        // Statistik untuk teknisi
        $totalPengajuan = Pengajuan::count();
        $totalPengaduan = Pengaduan::count();

        $pengajuanSelesai = Pengajuan::whereHas('statusPengajuan', function($query) {
            $query->where('status', 'Selesai');
        })->count();

        $pengaduanSelesai = Pengaduan::whereHas('statusPengaduan', function($query) {
            $query->where('status', 'Selesai');
        })->count();

        // Pengajuan terbaru
        $pengajuanTerbaru = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan'])
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)
                                   ->get();

        // Pengaduan terbaru
        $pengaduanTerbaru = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        return view('teknisi.dashboard', compact(
            'totalPengajuan', 
            'totalPengaduan', 
            'pengajuanSelesai', 
            'pengaduanSelesai',
            'pengajuanTerbaru',
            'pengaduanTerbaru'
        ));
    }

    /**
     * Daftar pengajuan untuk teknisi
     */
    public function pengajuan()
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        $statusPengajuan = StatusPengajuan::all();

        return view('teknisi.pengajuan.index', compact('pengajuan', 'statusPengajuan'));
    }

    /**
     * Detail pengajuan
     */
    public function showPengajuan($id)
    {
        $pengajuan = Pengajuan::with(['user', 'paketWifi', 'statusPengajuan', 'tagihan'])
                              ->findOrFail($id);

        $statusPengajuan = StatusPengajuan::all();

        return view('teknisi.pengajuan.show', compact('pengajuan', 'statusPengajuan'));
    }

    /**
     * Update status pengajuan
     */
    public function updatePengajuan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pengajuan_id' => 'required|exists:status_pengajuan,id_status_pengajuan',
            'catatan_teknisi' => 'nullable|string|max:1000',
        ], [
            'status_pengajuan_id.required' => 'Status pengajuan wajib dipilih.',
            'status_pengajuan_id.exists' => 'Status pengajuan yang dipilih tidak valid.',
            'catatan_teknisi.max' => 'Catatan teknisi maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengajuan = Pengajuan::findOrFail($id);
            $oldStatusId = $pengajuan->status_pengajuan_id;

            // Update pengajuan
            $pengajuan->update([
                'status_pengajuan_id' => $request->status_pengajuan_id,
                'catatan_teknisi' => $request->catatan_teknisi,
                'teknisi_id' => Auth::id(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi jika status berubah
            if ($oldStatusId != $request->status_pengajuan_id) {
                $this->sendPengajuanStatusChangeNotification($pengajuan, 'teknisi');
            }

            return redirect()->route('teknisi.pengajuan.show', $id)
                           ->with('success', 'Status pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengajuan oleh teknisi: ' . $e->getMessage(), [
                'pengajuan_id' => $id,
                'teknisi_id' => Auth::id(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat memperbarui status pengajuan.');
        }
    }

    /**
     * Daftar pengaduan untuk teknisi
     */
    public function pengaduan()
    {
        $pengaduan = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        $statusPengaduan = StatusPengaduan::all();

        return view('teknisi.pengaduan.index', compact('pengaduan', 'statusPengaduan'));
    }

    /**
     * Detail pengaduan
     */
    public function showPengaduan($id)
    {
        $pengaduan = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                             ->findOrFail($id);

        $statusPengaduan = StatusPengaduan::all();

        return view('teknisi.pengaduan.show', compact('pengaduan', 'statusPengaduan'));
    }

    /**
     * Update status pengaduan
     */
    public function updatePengaduan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pengaduan_id' => 'required|exists:status_pengaduan,id_status_pengaduan',
            'catatan_teknisi' => 'nullable|string|max:1000',
        ], [
            'status_pengaduan_id.required' => 'Status pengaduan wajib dipilih.',
            'status_pengaduan_id.exists' => 'Status pengaduan yang dipilih tidak valid.',
            'catatan_teknisi.max' => 'Catatan teknisi maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengaduan = Pengaduan::findOrFail($id);
            $oldStatusId = $pengaduan->status_id_status;

            // Update pengaduan
            $pengaduan->update([
                'status_id_status' => $request->status_pengaduan_id,
                'catatan_teknisi' => $request->catatan_teknisi,
                'teknisi_id' => Auth::id(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi jika status berubah
            if ($oldStatusId != $request->status_pengaduan_id) {
                $this->sendPengaduanStatusChangeNotification($pengaduan, 'teknisi');
            }

            return redirect()->route('teknisi.pengaduan.show', $id)
                           ->with('success', 'Status pengaduan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengaduan oleh teknisi: ' . $e->getMessage(), [
                'pengaduan_id' => $id,
                'teknisi_id' => Auth::id(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat memperbarui status pengaduan.');
        }
    }

    /**
     * Kirim notifikasi perubahan status pengajuan
     */
    private function sendPengajuanStatusChangeNotification($pengajuan, $updatedBy = 'teknisi')
    {
        try {
            $pengajuan->load(['user', 'paketWifi', 'statusPengajuan']);
            
            $user = $pengajuan->user;
            $status = $pengajuan->statusPengajuan ? $pengajuan->statusPengajuan->status : 'Unknown';
            $userName = $this->getUserName($pengajuan);
            $userEmail = $this->getUserEmail($pengajuan);
            $userPhone = $this->getUserPhone($pengajuan);
            $teknisiName = Auth::user()->nama ?? 'Teknisi';
            $paketName = $pengajuan->paketWifi ? $pengajuan->paketWifi->nama_paket : 'Paket WiFi';

            // Notifikasi ke user
            if ($user) {
                Notification::create([
                    'user_id' => $user->id_user,
                    'title' => 'Status Pengajuan Diperbarui',
                    'message' => "Status pengajuan WiFi Anda untuk paket {$paketName} telah diperbarui oleh teknisi menjadi: {$status}",
                    'type' => 'status_change_by_technician',
                    'data' => json_encode([
                        'pengajuan_id' => $pengajuan->id,
                        'new_status' => $pengajuan->status_pengajuan_id,
                        'status_name' => $status,
                        'updated_by' => $updatedBy,
                        'technician_name' => $teknisiName,
                        'user_name' => $userName
                    ]),
                    'is_read' => false,
                    'created_at' => now()
                ]);
            }

            // Notifikasi ke admin
            $admins = User::where('id_role', 1)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id_user,
                    'title' => 'Status Pengajuan Diperbarui oleh Teknisi',
                    'message' => "Teknisi {$teknisiName} telah memperbarui status pengajuan {$userName} menjadi: {$status}",
                    'type' => 'status_updated_by_technician',
                    'data' => json_encode([
                        'pengajuan_id' => $pengajuan->id,
                        'user_name' => $userName,
                        'technician_name' => $teknisiName,
                        'new_status' => $status,
                        'paket_name' => $paketName
                    ]),
                    'is_read' => false,
                    'created_at' => now()
                ]);

                // Email ke admin
                if ($admin->email) {
                    $emailMessage = "Teknisi {$teknisiName} telah memperbarui status pengajuan WiFi.\n\nDetail:\n- Pelanggan: {$userName}\n- Paket: {$paketName}\n- Status Baru: {$status}\n- Teknisi: {$teknisiName}\n- Tanggal: " . now()->format('d-m-Y H:i') . "\n\nSilakan cek dashboard untuk detail lebih lanjut.";
                    
                    $this->notificationController->sendEmailNotification(
                        $admin->email,
                        'Status Pengajuan Diperbarui oleh Teknisi',
                        $emailMessage
                    );
                }
            }

            // Email/WhatsApp ke user
            if ($userEmail) {
                $emailMessage = $this->getPengajuanStatusMessage($status, $paketName, $teknisiName);
                $this->notificationController->sendEmailNotification($userEmail, 'Status Pengajuan WiFi Diperbarui', $emailMessage);
            }

            if ($userPhone) {
                $whatsappMessage = $this->getWhatsAppPengajuanStatusMessage($status, $paketName, $teknisiName);
                $this->notificationController->sendWhatsAppNotification($userPhone, $whatsappMessage);
            }

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi perubahan status pengajuan: ' . $e->getMessage(), [
                'pengajuan_id' => $pengajuan->id,
                'teknisi_id' => Auth::id()
            ]);
        }
    }

    /**
     * Kirim notifikasi perubahan status pengaduan
     */
    private function sendPengaduanStatusChangeNotification($pengaduan, $updatedBy = 'teknisi')
    {
        try {
            $pengaduan->load(['user', 'keluhan', 'statusPengaduan']);
            
            $user = $pengaduan->user;
            $status = $pengaduan->statusPengaduan ? $pengaduan->statusPengaduan->status : 'Unknown';
            $userName = $user ? ($user->nama ?? 'User') : 'User';
            $userEmail = $user ? $user->email : null;
            $userPhone = $user ? $user->no_hp : null;
            $keluhanName = $pengaduan->keluhan ? $pengaduan->keluhan->nama_keluhan : 'Keluhan';
            $teknisiName = Auth::user()->nama ?? 'Teknisi';

            // Cek apakah status selesai (asumsi id_status_pengaduan = 2 untuk selesai)
            $isCompleted = $pengaduan->status_id_status == 2;

            // Notifikasi ke user
            if ($user) {
                Notification::create([
                    'user_id' => $user->id_user,
                    'title' => $isCompleted ? 'Pengaduan Selesai oleh Teknisi' : 'Status Pengaduan Diperbarui',
                    'message' => $isCompleted 
                        ? "Pengaduan WiFi Anda terkait {$keluhanName} telah diselesaikan oleh teknisi {$teknisiName}"
                        : "Status pengaduan WiFi Anda terkait {$keluhanName} telah diperbarui oleh teknisi {$teknisiName} menjadi: {$status}",
                    'type' => $isCompleted ? 'complaint_completed_by_technician' : 'complaint_status_change_by_technician',
                    'data' => json_encode([
                        'pengaduan_id' => $pengaduan->id,
                        'new_status' => $pengaduan->status_id_status,
                        'status_name' => $status,
                        'updated_by' => $updatedBy,
                        'technician_name' => $teknisiName,
                        'keluhan_name' => $keluhanName,
                        'is_completed' => $isCompleted
                    ]),
                    'is_read' => false,
                    'created_at' => now()
                ]);
            }

            // Notifikasi ke admin
            $admins = User::where('id_role', 1)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id_user,
                    'title' => 'Status Pengaduan Diperbarui oleh Teknisi',
                    'message' => "Teknisi {$teknisiName} telah memperbarui status pengaduan {$userName} menjadi: {$status}",
                    'type' => 'complaint_updated_by_technician',
                    'data' => json_encode([
                        'pengaduan_id' => $pengaduan->id,
                        'user_name' => $userName,
                        'technician_name' => $teknisiName,
                        'new_status' => $status,
                        'keluhan_name' => $keluhanName,
                        'is_completed' => $isCompleted
                    ]),
                    'is_read' => false,
                    'created_at' => now()
                ]);

                // Email ke admin
                if ($admin->email) {
                    $emailMessage = "Teknisi {$teknisiName} telah memperbarui status pengaduan WiFi.\n\nDetail:\n- Pelanggan: {$userName}\n- Jenis Keluhan: {$keluhanName}\n- Status Baru: {$status}\n- Teknisi: {$teknisiName}\n- Tanggal: " . now()->format('d-m-Y H:i') . "\n\nSilakan cek dashboard untuk detail lebih lanjut.";
                    
                    $this->notificationController->sendEmailNotification(
                        $admin->email,
                        'Status Pengaduan Diperbarui oleh Teknisi',
                        $emailMessage
                    );
                }
            }

            // Email/WhatsApp ke user
            if ($userEmail) {
                $emailMessage = $this->getPengaduanStatusMessage($status, $keluhanName, $isCompleted, $teknisiName);
                $emailTitle = $isCompleted ? 'Pengaduan WiFi Selesai' : 'Status Pengaduan WiFi Diperbarui';
                $this->notificationController->sendEmailNotification($userEmail, $emailTitle, $emailMessage);
            }

            if ($userPhone) {
                $whatsappMessage = $this->getWhatsAppPengaduanStatusMessage($status, $keluhanName, $isCompleted, $teknisiName);
                $this->notificationController->sendWhatsAppNotification($userPhone, $whatsappMessage);
            }

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi perubahan status pengaduan: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
                'teknisi_id' => Auth::id()
            ]);
        }
    }

    /**
     * Generate pesan email untuk status pengajuan
     */
    private function getPengajuanStatusMessage($status, $paketName, $teknisiName)
    {
        switch ($status) {
            case 'Disetujui':
                return "Selamat! Pengajuan WiFi Anda untuk paket {$paketName} telah DISETUJUI oleh teknisi {$teknisiName}. Tim teknis akan segera menghubungi Anda untuk proses instalasi.";
            case 'Ditolak':
                return "Mohon maaf, pengajuan WiFi Anda untuk paket {$paketName} tidak dapat disetujui oleh teknisi {$teknisiName}. Silakan hubungi customer service untuk informasi lebih lanjut.";
            case 'Dalam Proses':
                return "Pengajuan WiFi Anda untuk paket {$paketName} sedang dalam proses review oleh teknisi {$teknisiName}. Harap tunggu konfirmasi selanjutnya.";
            case 'Selesai':
                return "Pengajuan WiFi Anda untuk paket {$paketName} telah selesai diproses oleh teknisi {$teknisiName}. Layanan WiFi Anda sudah aktif dan siap digunakan.";
            default:
                return "Status pengajuan WiFi Anda untuk paket {$paketName} telah diperbarui oleh teknisi {$teknisiName} menjadi: {$status}";
        }
    }

    /**
     * Generate pesan WhatsApp untuk status pengajuan
     */
    private function getWhatsAppPengajuanStatusMessage($status, $paketName, $teknisiName)
    {
        switch ($status) {
            case 'Disetujui':
                return "🎉 *PENGAJUAN DISETUJUI!*\n\nSelamat! Pengajuan WiFi Anda untuk paket *{$paketName}* telah disetujui oleh teknisi *{$teknisiName}*.\n\n📞 Tim teknis akan segera menghubungi Anda untuk jadwal instalasi.\n\nTerima kasih! 🙏";
            case 'Selesai':
                return "✅ *PENGAJUAN SELESAI!*\n\nPengajuan WiFi Anda untuk paket *{$paketName}* telah selesai diproses oleh teknisi *{$teknisiName}*.\n\n🌐 Layanan WiFi Anda sudah aktif dan siap digunakan!\n\nSelamat menikmati layanan kami! 🎉";
            default:
                return "🔔 *STATUS UPDATE*\n\nStatus pengajuan WiFi Anda untuk paket *{$paketName}* telah diperbarui oleh teknisi *{$teknisiName}* menjadi: *{$status}*\n\nTerima kasih! 🙏";
        }
    }

    /**
     * Generate pesan email untuk status pengaduan
     */
    private function getPengaduanStatusMessage($status, $keluhanName, $isCompleted = false, $teknisiName = 'Tim Teknisi')
    {
        if ($isCompleted) {
            return "🎉 Kabar Baik!\n\nPengaduan WiFi Anda terkait {$keluhanName} telah SELESAI ditangani oleh teknisi {$teknisiName}.\n\n✅ Masalah Anda telah diselesaikan dengan baik.\n\nTerima kasih telah melaporkan masalah ini kepada kami. Jika masih ada kendala, jangan ragu untuk menghubungi kami kembali.\n\nSalam,\nTim Customer Service";
        }

        return "Status pengaduan WiFi Anda terkait {$keluhanName} telah diperbarui oleh teknisi {$teknisiName} menjadi: {$status}. Silakan cek dashboard untuk informasi lebih lanjut.";
    }

    /**
     * Generate pesan WhatsApp untuk status pengaduan
     */
    private function getWhatsAppPengaduanStatusMessage($status, $keluhanName, $isCompleted = false, $teknisiName = 'Tim Teknisi')
    {
        if ($isCompleted) {
            return "🎉 *PENGADUAN SELESAI!*\n\n✅ Pengaduan WiFi Anda terkait *{$keluhanName}* telah selesai ditangani oleh teknisi *{$teknisiName}*.\n\n🔧 Masalah Anda telah diselesaikan dengan baik.\n\n🙏 Terima kasih! Jika masih ada kendala, jangan ragu untuk menghubungi kami kembali.\n\n*Tim Customer Service*";
        }

        return "🔔 *STATUS UPDATE*\n\nStatus pengaduan WiFi Anda terkait *{$keluhanName}* telah diperbarui oleh teknisi *{$teknisiName}* menjadi: *{$status}*\n\nTerima kasih! 🙏";
    }

    /**
     * Helper methods
     */
    private function getUserEmail($pengajuan)
    {
        return $pengajuan->email ?? ($pengajuan->user ? $pengajuan->user->email : null);
    }

    private function getUserPhone($pengajuan)
    {
        return $pengajuan->no_telepon ?? ($pengajuan->user ? $pengajuan->user->no_hp : null);
    }

    private function getUserName($pengajuan)
    {
        return $pengajuan->nama_lengkap ?? ($pengajuan->user ? $pengajuan->user->nama : 'User');
    }

    /**
     * Get notifications for current technician
     */
    public function getNotifications()
    {
        return $this->notificationController->getNotifications();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        return $this->notificationController->markAsRead($id);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        return $this->notificationController->markAllAsRead();
    }
}
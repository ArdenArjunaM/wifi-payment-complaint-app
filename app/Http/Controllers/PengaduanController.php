<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;
use App\Models\StatusPengaduan;
use App\Models\Keluhan;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PengaduanController extends Controller
{
    protected $notificationController;

    // Constants for better maintainability
    const DEFAULT_STATUS_ID = 3;
    const COMPLETED_STATUS_ID = 2;
    const TEKNISI_ROLE_ID = 4;
    const ADMIN_ROLE = 1;
    const SUPERADMIN_ROLE = 3;

    public function __construct()
    {
        $this->notificationController = new NotificationController();
    }

    /**
     * Display a listing of the resource based on user role
     */
    public function index()
    {
        $pengaduan = $this->getPengaduanWithRelations();
        $statusPengaduan = StatusPengaduan::all();

        $view = match (auth()->user()->role) {
            self::SUPERADMIN_ROLE => 'superadmin.pengaduan.index',
            'teknisi' => 'teknisi.pengaduan.index',
            default => 'dashboard.pengaduan.index'
        };

        return view($view, compact('pengaduan', 'statusPengaduan'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $keluhan = Keluhan::all();
        
        $view = match (auth()->user()->role) {
            self::ADMIN_ROLE => 'dashboard.pengaduan.create',
            self::SUPERADMIN_ROLE => 'superadmin.pengaduan.create',
            default => 'user.pengaduanwifi'
        };

        return view($view, compact('keluhan'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $validated = $this->validatePengaduanData($request);

        try {
            $pengaduan = $this->createPengaduan($validated);
            $this->sendNewComplaintNotificationToAdmin($pengaduan);

            return $this->getStoreSuccessResponse();

        } catch (\Exception $e) {
            return $this->handleStoreError($e, $request);
        }
    }

    /**
     * Display the specified resource
     */
    public function show(string $id)
    {
        $pengaduan = $this->getPengaduanById($id);
        
        $view = match (auth()->user()->role) {
            self::SUPERADMIN_ROLE => 'superadmin.pengaduan.show',
            default => 'dashboard.pengaduan.show'
        };

        return view($view, compact('pengaduan'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(string $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        $keluhan = Keluhan::all();
        $status_pengaduan = StatusPengaduan::all();

        $view = match (auth()->user()->role) {
            self::SUPERADMIN_ROLE => 'superadmin.pengaduan.edit',
            default => 'dashboard.pengaduan.edit'
        };

        return view($view, compact('pengaduan', 'keluhan', 'status_pengaduan'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validateStatusUpdate($request);

        try {
            $pengaduan = $this->updatePengaduanStatus($id, $validated['status']);
            return $this->getUpdateSuccessResponse();

        } catch (\Exception $e) {
            return $this->handleUpdateError($e, $id, $request);
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(string $id)
    {
        try {
            $pengaduan = Pengaduan::findOrFail($id);
            $pengaduan->delete();

            return $this->getDeleteSuccessResponse();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pengaduan.');
        }
    }

    /**
     * Display pengaduan for teknisi role
     */
    public function indexTeknisi()
    {
        $pengaduan = Pengaduan::whereHas('user', function($query) {
            $query->where('role_id', self::TEKNISI_ROLE_ID);
        })->with(['user', 'keluhan', 'statusPengaduan'])->get();

        return view('teknisi.pengaduan.index', compact('pengaduan'));
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Get pengaduan with all necessary relations
     */
    private function getPengaduanWithRelations()
    {
        return Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])->get();
    }

    /**
     * Get pengaduan by ID with relations
     */
    private function getPengaduanById($id)
    {
        return Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])->findOrFail($id);
    }

    /**
     * Validate pengaduan data
     */
    private function validatePengaduanData(Request $request)
    {
        return $request->validate([
            'users_id_user' => 'required|exists:users,id_user',
            'jenis_keluhan' => 'required|exists:keluhan,id_keluhan',
        ]);
    }

    /**
     * Validate status update data
     */
    private function validateStatusUpdate(Request $request)
    {
        return $request->validate([
            'status' => 'required|exists:status_pengaduan,id_status_pengaduan',
        ]);
    }

    /**
     * Create new pengaduan
     */
    private function createPengaduan($validated)
    {
        return Pengaduan::create([
            'users_id_user' => $validated['users_id_user'],
            'keluhan_id_keluhan' => $validated['jenis_keluhan'],
            'status_id_status' => self::DEFAULT_STATUS_ID,
        ]);
    }

    /**
     * Update pengaduan status
     */
    private function updatePengaduanStatus($id, $newStatus)
    {
        $pengaduan = Pengaduan::with('statusPengaduan')->findOrFail($id);
        $oldStatusId = $pengaduan->status_id_status;

        $pengaduan->update(['status_id_status' => $newStatus]);
        $pengaduan->refresh();

        // Send notification if status changed
        if ($oldStatusId != $newStatus) {
            $this->sendStatusChangeNotification($pengaduan);
        }

        return $pengaduan;
    }

    /**
     * Get success response for store operation
     */
    private function getStoreSuccessResponse()
    {
        $userRole = auth()->user()->role;
        
        if ($userRole === self::ADMIN_ROLE || $userRole === self::SUPERADMIN_ROLE) {
            return redirect()->route('pengaduanwifi')->with('success', 'Pengaduan berhasil dibuat!');
        }
        
        return redirect()->back()->with('success', 'Pengaduan Anda berhasil dikirim! Kami akan segera memproses pengaduan Anda.');
    }

    /**
     * Get success response for update operation
     */
    private function getUpdateSuccessResponse()
    {
        $route = auth()->user()->role === self::SUPERADMIN_ROLE 
            ? 'superadmin.pengaduan.index' 
            : 'dashboard.pengaduan.index';
            
        return redirect()->route($route)->with('success', 'Pengaduan berhasil diperbarui!');
    }

    /**
     * Get success response for delete operation
     */
    private function getDeleteSuccessResponse()
    {
        $route = auth()->user()->role === self::SUPERADMIN_ROLE 
            ? 'superadmin.pengaduan.index' 
            : 'dashboard.pengaduan.index';
            
        return redirect()->route($route)->with('success', 'Pengaduan berhasil dihapus!');
    }

    /**
     * Handle store operation error
     */
    private function handleStoreError(\Exception $e, Request $request)
    {
        Log::error('Gagal menyimpan pengaduan: ' . $e->getMessage(), [
            'user_id' => $request->users_id_user,
            'input' => $request->all()
        ]);
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menyimpan pengaduan. Silakan coba lagi.')
            ->withInput();
    }

    /**
     * Handle update operation error
     */
    private function handleUpdateError(\Exception $e, $id, Request $request)
    {
        Log::error('Gagal memperbarui pengaduan: ' . $e->getMessage(), [
            'pengaduan_id' => $id,
            'input' => $request->all()
        ]);

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengaduan.');
    }

    // ========================================
    // NOTIFICATION METHODS
    // ========================================

    /**
     * Send new complaint notification to admin
     */
    private function sendNewComplaintNotificationToAdmin($pengaduan)
    {
        $pengaduan->load(['user', 'keluhan']);
        
        $user = $pengaduan->user;
        $userData = [
            'name' => $user->nama ?? 'User',
            'email' => $user->email ?? null,
            'phone' => $user->no_hp ?? null,
            'address' => $user->alamat ?? 'Tidak tersedia'
        ];
        
        $keluhanName = $pengaduan->keluhan->nama_keluhan ?? 'Keluhan';
        
        $admins = $this->getAdminUsers();
        
        foreach ($admins as $admin) {
            $this->createNotificationForAdmin($admin, $pengaduan, $userData, $keluhanName);
            $this->sendEmailToAdmin($admin, $pengaduan, $userData, $keluhanName);
            $this->sendWhatsAppToAdmin($admin, $pengaduan, $userData, $keluhanName);
        }
    }

    /**
     * Send status change notification to user
     */
    private function sendStatusChangeNotification($pengaduan)
    {
        $pengaduan->load(['user', 'keluhan', 'statusPengaduan']);
        
        $user = $pengaduan->user;
        $status = $pengaduan->statusPengaduan->status ?? 'Unknown';
        $keluhanName = $pengaduan->keluhan->nama_keluhan ?? 'Keluhan';
        $isCompleted = $pengaduan->status_id_status == self::COMPLETED_STATUS_ID;

        $this->createNotificationForUser($user, $pengaduan, $status, $keluhanName, $isCompleted);
        $this->sendEmailToUser($user, $status, $keluhanName, $isCompleted);
        $this->sendWhatsAppToUser($user, $status, $keluhanName, $isCompleted);

        $this->logStatusChange($pengaduan, $user, $status, $isCompleted);
    }

    /**
     * Get admin users
     */
    private function getAdminUsers()
    {
        return User::where(function($query) {
            $query->where('role', 1)
                  ->orWhere('role', 3)
                  ->orWhere('role', self::ADMIN_ROLE)
                  ->orWhere('role', self::SUPERADMIN_ROLE);
        })->get();
    }

    /**
     * Create notification for admin
     */
    private function createNotificationForAdmin($admin, $pengaduan, $userData, $keluhanName)
    {
        Notification::create([
            'user_id' => $admin->id_user,
            'title' => 'Pengaduan WiFi Baru',
            'message' => "Pengaduan WiFi baru dari {$userData['name']} terkait {$keluhanName}",
            'type' => 'new_complaint',
            'data' => json_encode([
                'pengaduan_id' => $pengaduan->id,
                'user_name' => $userData['name'],
                'keluhan_name' => $keluhanName,
                'user_email' => $userData['email'],
                'user_phone' => $userData['phone']
            ]),
            'is_read' => false,
            'created_at' => now()
        ]);
    }

    /**
     * Create notification for user
     */
    private function createNotificationForUser($user, $pengaduan, $status, $keluhanName, $isCompleted)
    {
        Notification::create([
            'user_id' => $user->id_user,
            'title' => $isCompleted ? 'Pengaduan Selesai' : 'Status Pengaduan Berubah',
            'message' => $isCompleted 
                ? "Pengaduan WiFi Anda terkait {$keluhanName} telah selesai ditangani" 
                : "Status pengaduan WiFi Anda terkait {$keluhanName} telah berubah menjadi: {$status}",
            'type' => $isCompleted ? 'complaint_completed' : 'complaint_status_change',
            'data' => json_encode([
                'pengaduan_id' => $pengaduan->id,
                'new_status' => $pengaduan->status_id_status,
                'status_name' => $status,
                'user_name' => $user->nama ?? 'User',
                'user_email' => $user->email ?? null,
                'user_phone' => $user->no_hp ?? null,
                'keluhan_name' => $keluhanName,
                'is_completed' => $isCompleted
            ]),
            'is_read' => false,
            'created_at' => now()
        ]);
    }

    /**
     * Send email to admin
     */
    private function sendEmailToAdmin($admin, $pengaduan, $userData, $keluhanName)
    {
        if (!$admin->email) return;

        $emailMessage = "Ada pengaduan WiFi baru dari {$userData['name']} terkait {$keluhanName}.\n\n" .
                       "Detail Pengaduan:\n" .
                       "- Nama: {$userData['name']}\n" .
                       "- Email: {$userData['email']}\n" .
                       "- No HP: {$userData['phone']}\n" .
                       "- Alamat: {$userData['address']}\n" .
                       "- Jenis Keluhan: {$keluhanName}\n" .
                       "- Status: Pending\n" .
                       "- Tanggal: " . $pengaduan->created_at->format('d-m-Y H:i') . "\n\n" .
                       "Silakan cek dashboard untuk detail lebih lanjut dan tindak lanjut yang diperlukan.";
        
        $this->notificationController->sendEmailNotification(
            $admin->email, 
            'Pengaduan WiFi Baru', 
            $emailMessage
        );
    }

    /**
     * Send WhatsApp to admin
     */
    private function sendWhatsAppToAdmin($admin, $pengaduan, $userData, $keluhanName)
    {
        if (!$admin->no_hp) return;

        $whatsappMessage = "🚨 *Pengaduan WiFi Baru*\n\n" .
                          "Pelanggan: {$userData['name']}\n" .
                          "Email: {$userData['email']}\n" .
                          "No HP: {$userData['phone']}\n" .
                          "Alamat: {$userData['address']}\n" .
                          "Jenis Keluhan: {$keluhanName}\n" .
                          "Status: Pending\n" .
                          "Tanggal: " . $pengaduan->created_at->format('d-m-Y H:i') . "\n\n" .
                          "Silakan cek dashboard untuk menindaklanjuti pengaduan ini.";
        
        $this->notificationController->sendWhatsAppNotification($admin->no_hp, $whatsappMessage);
    }

    /**
     * Send email to user
     */
    private function sendEmailToUser($user, $status, $keluhanName, $isCompleted)
    {
        if (!$user->email) return;

        $emailMessage = $this->getComplaintStatusMessage($status, $keluhanName, $isCompleted);
        $emailTitle = $isCompleted ? 'Pengaduan WiFi Selesai' : 'Status Pengaduan WiFi Berubah';
        
        $this->notificationController->sendEmailNotification($user->email, $emailTitle, $emailMessage);
    }

    /**
     * Send WhatsApp to user
     */
    private function sendWhatsAppToUser($user, $status, $keluhanName, $isCompleted)
    {
        if (!$user->no_hp) return;

        $whatsappMessage = $this->getWhatsAppComplaintStatusMessage($status, $keluhanName, $isCompleted);
        $this->notificationController->sendWhatsAppNotification($user->no_hp, $whatsappMessage);
    }

    /**
     * Log status change
     */
    private function logStatusChange($pengaduan, $user, $status, $isCompleted)
    {
        Log::info('Status change notification sent', [
            'pengaduan_id' => $pengaduan->id,
            'user_id' => $user->id_user,
            'new_status' => $pengaduan->status_id_status,
            'status_name' => $status,
            'is_completed' => $isCompleted
        ]);
    }

    /**
     * Generate email message based on complaint status
     */
    private function getComplaintStatusMessage($status, $keluhanName, $isCompleted = false)
    {
        if ($isCompleted) {
            return "🎉 Kabar Baik!\n\n" .
                   "Pengaduan WiFi Anda terkait {$keluhanName} telah SELESAI ditangani dengan baik.\n\n" .
                   "✅ Tim teknis kami telah menyelesaikan penanganan keluhan Anda.\n\n" .
                   "Terima kasih telah melaporkan masalah ini kepada kami. Kepuasan Anda adalah prioritas utama kami.\n\n" .
                   "Jika masih ada kendala atau pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami kembali.\n\n" .
                   "Salam,\nTim Customer Service";
        }

        return match (strtolower($status)) {
            'diproses', 'dalam proses' => 
                "Pengaduan WiFi Anda terkait {$keluhanName} sedang dalam proses penanganan. Tim teknis kami akan segera menindaklanjuti keluhan Anda. Terima kasih atas kesabaran Anda.",
            'selesai', 'resolved' => 
                "Pengaduan WiFi Anda terkait {$keluhanName} telah SELESAI ditangani. Terima kasih telah melaporkan masalah ini kepada kami. Jika masih ada kendala, jangan ragu untuk menghubungi kami kembali.",
            'ditolak', 'rejected' => 
                "Pengaduan WiFi Anda terkait {$keluhanName} tidak dapat diproses lebih lanjut. Silakan hubungi customer service kami untuk penjelasan lebih detail.",
            'pending' => 
                "Pengaduan WiFi Anda terkait {$keluhanName} telah kami terima dan sedang menunggu review. Kami akan segera menindaklanjuti pengaduan Anda.",
            default => 
                "Status pengaduan WiFi Anda terkait {$keluhanName} telah berubah menjadi: {$status}. Silakan cek dashboard untuk informasi lebih lanjut."
        };
    }

    /**
     * Generate WhatsApp message based on complaint status
     */
    private function getWhatsAppComplaintStatusMessage($status, $keluhanName, $isCompleted = false)
    {
        if ($isCompleted) {
            return "🎉 *PENGADUAN SELESAI!*\n\n" .
                   "✅ Pengaduan WiFi Anda terkait *{$keluhanName}* telah selesai ditangani dengan baik.\n\n" .
                   "🔧 Tim teknis kami telah menyelesaikan penanganan keluhan Anda dengan tuntas.\n\n" .
                   "🙏 Terima kasih telah melaporkan masalah ini kepada kami. Kepuasan Anda adalah prioritas utama kami.\n\n" .
                   "📞 Jika masih ada kendala atau pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami kembali.\n\n" .
                   "*Tim Customer Service*";
        }

        return match (strtolower($status)) {
            'diproses', 'dalam proses' => 
                "⚙️ *STATUS UPDATE*\n\nPengaduan WiFi Anda terkait *{$keluhanName}* sedang dalam proses penanganan.\n\n🔧 Tim teknis kami akan segera menindaklanjuti keluhan Anda.\n\nTerima kasih atas kesabaran Anda! 🙏",
            'selesai', 'resolved' => 
                "✅ *PENGADUAN SELESAI*\n\nPengaduan WiFi Anda terkait *{$keluhanName}* telah selesai ditangani.\n\n🎉 Terima kasih telah melaporkan masalah ini kepada kami.\n\nJika masih ada kendala, jangan ragu untuk menghubungi kami kembali! 📞",
            'ditolak', 'rejected' => 
                "❌ *Pengaduan Tidak Diproses*\n\nPengaduan WiFi Anda terkait *{$keluhanName}* tidak dapat diproses lebih lanjut.\n\n📞 Silakan hubungi customer service kami untuk penjelasan lebih detail.\n\nTerima kasih atas pengertiannya.",
            'pending' => 
                "⏳ *Pengaduan Diterima*\n\nPengaduan WiFi Anda terkait *{$keluhanName}* telah kami terima dan sedang menunggu review.\n\n✅ Kami akan segera menindaklanjuti pengaduan Anda.\n\nTerima kasih! 🙏",
            default => 
                "🔔 *STATUS UPDATE*\n\nStatus pengaduan WiFi Anda terkait *{$keluhanName}* telah berubah menjadi: *{$status}*\n\nSilakan cek dashboard untuk informasi lebih lanjut."
        };
    }

    // ========================================
    // NOTIFICATION DELEGATION METHODS
    // ========================================

    /**
     * Get notifications for current user - delegated to NotificationController
     */
    public function getNotifications()
    {
        return $this->notificationController->getNotifications();
    }

    /**
     * Mark notification as read - delegated to NotificationController
     */
    public function markAsRead($id)
    {
        return $this->notificationController->markAsRead($id);
    }

    /**
     * Mark all notifications as read - delegated to NotificationController
     */
    public function markAllAsRead()
    {
        return $this->notificationController->markAllAsRead();
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class NotificationController extends Controller
{
    private $twilioClient;

    public function __construct()
    {
        // Inisialisasi Twilio Client
        $sid = env('AC58fd7c748926f618b8ef51b43848c5e4');
        $token = env('fd20241c3c55459fa2b243111feb33cc');
        
        if ($sid && $token) {
            $this->twilioClient = new Client($sid, $token);
        }
    }

    

    /**
     * Send email notification
     */
    public function sendEmailNotification($to, $subject, $message)
    {
        try {
            Mail::raw($message, function ($mail) use ($to, $subject) {
                $mail->to($to)
                     ->subject($subject);
            });
            
            Log::info("Email sent successfully to: {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$to}: " . $e->getMessage());
            return false;
        }
    }

    

    /**
     * Send WhatsApp notification via Twilio
     */
    public function sendWhatsAppNotification($phoneNumber, $message)
    {
        if (!$this->twilioClient) {
            Log::error("Twilio client not initialized. Check your credentials.");
            return false;
        }

        try {
            // Format nomor telepon Indonesia
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            // Kirim pesan WhatsApp
            $twilioMessage = $this->twilioClient->messages->create(
                "whatsapp:{$formattedPhone}", // to
                [
                    "from" => env('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886'),
                    "body" => $message
                ]
            );

            Log::info("WhatsApp sent successfully via Twilio. Message SID: " . $twilioMessage->sid);
            return $twilioMessage->sid;

        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp via Twilio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp with Template (untuk content template)
     */
    public function sendWhatsAppTemplate($phoneNumber, $templateData = [])
    {
        if (!$this->twilioClient) {
            Log::error("Twilio client not initialized. Check your credentials.");
            return false;
        }

        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            $messageParams = [
                "from" => env('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886')
            ];

            // Jika menggunakan content template
            if (env('TWILIO_CONTENT_SID')) {
                $messageParams["contentSid"] = env('TWILIO_CONTENT_SID');
                
                // Format variables untuk template
                if (!empty($templateData)) {
                    $messageParams["contentVariables"] = json_encode($templateData);
                }
            } else {
                // Fallback ke pesan biasa jika tidak ada template
                $messageParams["body"] = "Notifikasi dari WiFi Provider";
            }

            $twilioMessage = $this->twilioClient->messages->create(
                "whatsapp:{$formattedPhone}",
                $messageParams
            );

            Log::info("WhatsApp template sent successfully. Message SID: " . $twilioMessage->sid);
            return $twilioMessage->sid;

        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp template: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor telepon Indonesia untuk Twilio
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Hapus karakter non-digit
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Konversi format Indonesia
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (substr($cleanPhone, 0, 2) !== '62') {
            $cleanPhone = '62' . $cleanPhone;
        }
        
        return '+' . $cleanPhone;
    }

    /**
     * Get delivery status dari Twilio
     */
    public function getMessageStatus($messageSid)
    {
        if (!$this->twilioClient) {
            return null;
        }

        try {
            $message = $this->twilioClient->messages($messageSid)->fetch();
            return [
                'status' => $message->status,
                'error_code' => $message->errorCode,
                'error_message' => $message->errorMessage,
                'date_sent' => $message->dateSent
            ];
        } catch (\Exception $e) {
            Log::error("Failed to get message status: " . $e->getMessage());
            return null;
        }
    }

    // =================== PENGADUAN NOTIFICATION METHODS ===================

    /**
     * Buat notifikasi untuk pengaduan baru
     */
    public function createPengaduanNotification($pengaduan, $type = 'pengaduan_baru')
    {
        switch ($type) {
            case 'pengaduan_baru':
                $this->notifyPengaduanBaru($pengaduan);
                break;
            case 'status_update':
                $this->notifyStatusUpdate($pengaduan);
                break;
            case 'pengaduan_selesai':
                $this->notifyPengaduanSelesai($pengaduan);
                break;
        }
    }

    /**
     * Notifikasi pengaduan baru
     */
    private function notifyPengaduanBaru($pengaduan)
    {
        // Notifikasi untuk admin
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            // Simpan notifikasi di database
            $notification = Notification::create([
                'user_id' => $admin->id_user,
                'pengaduan_id' => $pengaduan->id,
                'title' => 'Pengaduan Baru',
                'message' => "Pengaduan baru dari {$pengaduan->user->nama} dengan keluhan: {$pengaduan->keluhan->nama_keluhan}",
                'type' => 'pengaduan_baru',
                'is_read' => false
            ]);

            // Kirim email jika admin memiliki email
            if ($admin->email) {
                $subject = 'Pengaduan Baru - WiFi Provider';
                $message = "Hai {$admin->nama},\n\n";
                $message .= "Ada pengaduan baru yang perlu ditangani:\n\n";
                $message .= "Dari: {$pengaduan->user->nama}\n";
                $message .= "Keluhan: {$pengaduan->keluhan->nama_keluhan}\n";
                $message .= "Tanggal: " . $pengaduan->created_at->format('d-m-Y H:i') . "\n\n";
                $message .= "Silakan login ke dashboard untuk menangani pengaduan ini.";
                
                $this->sendEmailNotification($admin->email, $subject, $message);
            }

            // Kirim WhatsApp jika admin memiliki nomor telepon
            if ($admin->phone_number) {
                $waMessage = "🔔 *Pengaduan Baru*\n\n";
                $waMessage .= "Dari: {$pengaduan->user->nama}\n";
                $waMessage .= "Keluhan: {$pengaduan->keluhan->nama_keluhan}\n";
                $waMessage .= "Tanggal: " . $pengaduan->created_at->format('d-m-Y H:i') . "\n\n";
                $waMessage .= "Silakan cek dashboard untuk detail lengkap.";
                
                $this->sendWhatsAppNotification($admin->phone_number, $waMessage);
            }
        }

        // Notifikasi konfirmasi untuk user yang mengajukan
        $user = $pengaduan->user;
        
        $userNotification = Notification::create([
            'user_id' => $user->id_user,
            'pengaduan_id' => $pengaduan->id,
            'title' => 'Pengaduan Berhasil Dikirim',
            'message' => "Pengaduan Anda tentang {$pengaduan->keluhan->nama_keluhan} telah berhasil dikirim dan sedang diproses.",
            'type' => 'pengaduan_confirmation',
            'is_read' => false
        ]);

        // Kirim konfirmasi ke user via WhatsApp
        if ($user->phone_number) {
            $waMessage = "✅ *Pengaduan Berhasil Dikirim*\n\n";
            $waMessage .= "Terima kasih! Pengaduan Anda tentang {$pengaduan->keluhan->nama_keluhan} telah kami terima.\n\n";
            $waMessage .= "Tim kami akan segera memproses pengaduan Anda. Anda akan mendapat notifikasi update status.";
            
            $this->sendWhatsAppNotification($user->phone_number, $waMessage);
        }
    }

    /**
     * Notifikasi update status pengaduan
     */
    private function notifyStatusUpdate($pengaduan, $statusLama = null, $statusBaru = null)
    {
        $user = $pengaduan->user;
        
        // Simpan notifikasi di database
        $notification = Notification::create([
            'user_id' => $user->id_user,
            'pengaduan_id' => $pengaduan->id,
            'title' => 'Status Pengaduan Diperbarui',
            'message' => "Status pengaduan Anda tentang {$pengaduan->keluhan->nama_keluhan} telah diubah menjadi: {$pengaduan->statusPengaduan->status}",
            'type' => 'status_update',
            'is_read' => false
        ]);

        // Kirim email update
        if ($user->email) {
            $subject = 'Update Status Pengaduan - WiFi Provider';
            $message = "Hai {$user->nama},\n\n";
            $message .= "Status pengaduan Anda telah diperbarui:\n\n";
            $message .= "Keluhan: {$pengaduan->keluhan->nama_keluhan}\n";
            if ($statusLama && $statusBaru) {
                $message .= "Status Lama: {$statusLama}\n";
                $message .= "Status Baru: {$statusBaru}\n";
            } else {
                $message .= "Status Saat Ini: {$pengaduan->statusPengaduan->status}\n";
            }
            $message .= "Tanggal Update: " . now()->format('d-m-Y H:i') . "\n\n";
            $message .= "Terima kasih atas kesabaran Anda.";
            
            $this->sendEmailNotification($user->email, $subject, $message);
        }

        // Kirim WhatsApp update
        if ($user->phone_number) {
            $waMessage = "🔄 *Status Pengaduan Diperbarui*\n\n";
            $waMessage .= "Hai {$user->nama},\n\n";
            $waMessage .= "Keluhan: {$pengaduan->keluhan->nama_keluhan}\n";
            $waMessage .= "Status: {$pengaduan->statusPengaduan->status}\n";
            $waMessage .= "Update: " . now()->format('d-m-Y H:i') . "\n\n";
            $waMessage .= "Terima kasih atas kesabaran Anda! 🙏";
            
            $this->sendWhatsAppNotification($user->phone_number, $waMessage);
        }
    }

    /**
     * Notifikasi pengaduan selesai
     */
    private function notifyPengaduanSelesai($pengaduan)
    {
        $user = $pengaduan->user;
        
        // Simpan notifikasi di database
        $notification = Notification::create([
            'user_id' => $user->id_user,
            'pengaduan_id' => $pengaduan->id,
            'title' => 'Pengaduan Selesai',
            'message' => "Pengaduan Anda tentang {$pengaduan->keluhan->nama_keluhan} telah selesai ditangani. Terima kasih atas kesabaran Anda.",
            'type' => 'pengaduan_selesai',
            'is_read' => false
        ]);

        // Kirim email selesai
        if ($user->email) {
            $subject = 'Pengaduan Selesai - WiFi Provider';
            $message = "Hai {$user->nama},\n\n";
            $message .= "Kabar baik! Pengaduan Anda telah selesai ditangani:\n\n";
            $message .= "Keluhan: {$pengaduan->keluhan->nama_keluhan}\n";
            $message .= "Status: Selesai\n";
            $message .= "Tanggal Selesai: " . now()->format('d-m-Y H:i') . "\n\n";
            $message .= "Terima kasih telah mempercayai layanan kami. Jika ada kendala lain, jangan ragu untuk menghubungi kami.";
            
            $this->sendEmailNotification($user->email, $subject, $message);
        }

        // Kirim WhatsApp selesai
        if ($user->phone_number) {
            $waMessage = "✅ *Pengaduan Selesai*\n\n";
            $waMessage .= "Hai {$user->nama},\n\n";
            $waMessage .= "Kabar baik! Pengaduan Anda telah selesai ditangani:\n\n";
            $waMessage .= "Keluhan: {$pengaduan->keluhan->nama_keluhan}\n";
            $waMessage .= "Status: ✅ Selesai\n";
            $waMessage .= "Selesai: " . now()->format('d-m-Y H:i') . "\n\n";
            $waMessage .= "Terima kasih atas kepercayaan Anda! 🙏\n";
            $waMessage .= "Ada kendala lain? Jangan ragu menghubungi kami.";
            
            $this->sendWhatsAppNotification($user->phone_number, $waMessage);
        }
    }

    // =================== EXISTING METHODS ===================

    /**
     * Get notifications for current user
     */
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', Auth::id())
                                        ->where('is_read', false)
                                        ->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->first();
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                   ->where('is_read', false)
                   ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get notification details
     */
    public function show($id)
{
    $notification = Notification::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->with('pengaduan.keluhan')
                                ->first();
    
    if (!$notification) {
        return response()->json(['error' => 'Notification not found'], 404);
    }

    // Mark as read when viewed
    if (!$notification->is_read) {
        $notification->markAsRead();
    }

    // Format response to match JavaScript expectations
    return response()->json([
        'notification' => [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'type' => $notification->type ?? 'info',
            'is_read' => (bool) $notification->is_read,
            'created_at' => $notification->created_at->format('d M Y H:i:s'),
            'created_at_human' => $notification->created_at->diffForHumans(),
            'data' => [
                'pengaduan' => $notification->pengaduan ? [
                    'id' => $notification->pengaduan->id,
                    'keluhan' => $notification->pengaduan->keluhan->nama ?? null,
                    'status' => $notification->pengaduan->status ?? null,
                    // tambahkan field lain yang diperlukan
                ] : null
            ],
            'url' => $notification->url ?? null
        ]
    ]);
}

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->first();
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}
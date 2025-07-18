<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pengaduan;

class PengaduanStatusUpdated extends Notification
{
    private $pengaduan;

    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function via($notifiable)
    {
        return ['database']; // Menggunakan database untuk menyimpan notifikasi
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Status pengaduan Anda telah diperbarui menjadi ' . $this->pengaduan->status,
            'pengaduan_id' => $this->pengaduan->id,
            'status' => $this->pengaduan->status,
        ];
    }
}

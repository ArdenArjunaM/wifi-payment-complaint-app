<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Selamat Datang!',
                'message' => 'Terima kasih telah bergabung dengan layanan WiFi kami.',
                'type' => 'welcome',
                'is_read' => false,
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pembayaran Berhasil',
                'message' => 'Pembayaran paket WiFi bulanan Anda telah berhasil diproses.',
                'type' => 'payment',
                'is_read' => false,
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Peringatan Sistem',
                'message' => 'Koneksi WiFi akan mengalami maintenance pada tanggal 20 Juni 2025.',
                'type' => 'warning',
                'is_read' => false,
            ]);
        }
    }
}
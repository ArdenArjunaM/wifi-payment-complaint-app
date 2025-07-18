<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengajuan;
use App\Models\StatusPengajuan;
use App\Models\PaketWifi;
use Carbon\Carbon;

class PengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua pengguna yang sudah ada
        $users = \App\Models\User::all();

        // Periksa jika ada pengguna yang ditemukan
        if ($users->isEmpty()) {
            echo "Tidak ada pengguna di database!";
            return;
        }

        // Ambil semua paket WiFi yang tersedia
        $paketWiFi = PaketWifi::all();

        // Periksa jika paket WiFi ada
        if ($paketWiFi->isEmpty()) {
            echo "Tidak ada paket WiFi di database!";
            return;
        }

        // Ambil status pengajuan 'Selesai'
        $statusSelesai = StatusPengajuan::where('status', 'Selesai')->first();

        // Cek jika status pengajuan 'Selesai' ada
        if (!$statusSelesai) {
            $statusSelesai = StatusPengajuan::create([
                'status' => 'Selesai',
                'created_at' => Carbon::create(2024, 12, 1),  
                'updated_at' => Carbon::create(2024, 12, 1),  
            ]);
        }

        // Cek jika ada paket WiFi dan lakukan loop untuk setiap pengguna
        foreach ($users as $user) {
            // Ambil paket WiFi secara acak
            $selectedPaketWiFi = $paketWiFi->random(); // Memilih paket WiFi secara acak

           

            // Menambahkan pengajuan untuk setiap pengguna
            Pengajuan::create([
                'users_id_user' => $user->id_user,  // Menghubungkan pengajuan dengan pengguna
                'paket_wifi_id_paket_wifi' => $selectedPaketWiFi->id_paket_wifi,  // Menetapkan paket WiFi yang dipilih
                'status_pengajuan_id' => $statusSelesai->id_status_pengajuan,  // Status pengajuan adalah 'Selesai'
                'created_at' => Carbon::create(2024, 12, 1),  // Tanggal pembuatan pengajuan
                'updated_at' => Carbon::create(2024, 12, 1),  // Tanggal pembaruan pengajuan
            ]);
        }
    }
}

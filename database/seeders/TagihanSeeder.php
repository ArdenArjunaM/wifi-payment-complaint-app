<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Pengajuan;
use App\Models\PaketWifi;
use App\Models\StatusTagihan;

class TagihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua pengajuan yang sudah selesai
        $pengajuanSelesai = Pengajuan::whereHas('statusPengajuan', function ($query) {
            $query->where('status', 'Selesai');
        })->get();

        // Ambil status tagihan 'Belum Dibayar' (ID = 1)
        $statusTagihan = StatusTagihan::where('status', 'Dibayar')->first();

        // Cek jika status tagihan 'Belum Dibayar' ada
        if (!$statusTagihan) {
            $statusTagihan = StatusTagihan::create([
                'status' => 'Dibayar',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Looping pengajuan yang selesai dan buat tagihan untuk setiap pengajuan
        foreach ($pengajuanSelesai as $pengajuan) {
            // Ambil paket WiFi yang dipilih pengguna
            $paketWiFi = PaketWifi::find($pengajuan->paket_wifi_id_paket_wifi);

            // Jika paket WiFi ditemukan, buat tagihan
            if ($paketWiFi) {
                // Hitung jumlah tagihan berdasarkan harga bulanan paket WiFi
                $jumlahTagihan = $paketWiFi->harga_bulanan;

                // Tentukan tanggal tagihan mulai (Januari 2025, tanggal 25)
                $tanggalTagihan = Carbon::parse('2024-12-25'); // Menetapkan tanggal 25 Desember 2024 sebagai tagihan pertama

                // Looping untuk membuat tagihan setiap bulan dari Desember 2024 hingga Mei 2025
                for ($i = 0; $i < 6; $i++) {
                    // Tentukan jatuh tempo pada tanggal 25 setiap bulan
                    $jatuhTempo = $tanggalTagihan->copy()->addMonth()->day(25); // Set setiap bulan tanggal 25

                    // Menambahkan tagihan
                    DB::table('tagihan')->insert([
                        'jumlah_tagihan' => $jumlahTagihan,  // Jumlah tagihan berdasarkan harga bulanan paket WiFi
                        'jatuh_tempo' => $jatuhTempo,  // Tanggal jatuh tempo (tanggal 25 setiap bulan)
                        'users_id_user' => $pengajuan->users_id_user,  // Menghubungkan tagihan dengan pengguna
                        'paket_wifi_id_paket_wifi' => $paketWiFi->id_paket_wifi,  // ID paket WiFi
                        'status_tagihan_id' => $statusTagihan->id_status_tagihan,  // Status tagihan 'Belum Dibayar'
                        'created_at' => $tanggalTagihan,  // Sesuaikan dengan tanggal tagihan
                        'updated_at' => $tanggalTagihan,  // Sesuaikan dengan tanggal tagihan
                    ]);

                    // Update bulan tagihan untuk bulan berikutnya
                    $tanggalTagihan->addMonth(); // Pindah ke bulan berikutnya untuk tagihan selanjutnya
                }
            }
        }
    }
}

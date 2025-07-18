<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\StatusPengajuan;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\PaketWifi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatusPengajuanController extends Controller
{
    /**
     * Mengupdate status pengajuan yang ada dan membuat tagihan otomatis jika disetujui.
     */
    public function update(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|string|max:50|unique:status_pengajuan,status,' . $id,
        ]);

        // Cari status pengajuan berdasarkan ID
        $statusPengajuan = StatusPengajuan::find($id);

        if (!$statusPengajuan) {
            return response()->json(['message' => 'Status Pengajuan tidak ditemukan!'], 404);
        }

        // Update status pengajuan
        $statusPengajuan->status = $request->status;
        $statusPengajuan->save();

        // Jika status pengajuan menjadi "Disetujui", buatkan tagihan otomatis
        if ($statusPengajuan->status == 'Disetujui') {
            // Dapatkan pengajuan yang terkait
            $pengajuan = Pengajuan::where('status_pengajuan_id', $id)->first();

            if ($pengajuan) {
                // Ambil informasi pengguna dan paket wifi
                $user = User::find($pengajuan->users_id_user);
                $paketWifi = PaketWifi::find($pengajuan->paket_wifi_id_paket_wifi);

                // Buat tagihan baru
                $tagihan = Tagihan::create([
                    'jumlah_tagihan' => $paketWifi->harga_bulanan,
                    'jatuh_tempo' => Carbon::now()->addMonth(),  // Jatuh tempo bulan depan
                    'users_id_user' => $user->id_user,
                    'paket_wifi_id_paket_wifi' => $paketWifi->id_paket_wifi,
                    'status_tagihan_id' => 1  // 1 adalah status "Belum Dibayar"
                ]);

                return response()->json([
                    'message' => 'Status Pengajuan berhasil diupdate dan tagihan berhasil dibuat!',
                    'status_pengajuan' => $statusPengajuan,
                    'tagihan' => $tagihan
                ]);
            }

            return response()->json(['message' => 'Pengajuan tidak ditemukan!'], 404);
        }

        return response()->json([
            'message' => 'Status Pengajuan berhasil diupdate!',
            'status_pengajuan' => $statusPengajuan
        ]);
    }
}

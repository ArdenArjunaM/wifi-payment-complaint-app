<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaketWifi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar paket wifi untuk pengajuan
     */
    public function showPaketWifi()
    {
        // Mengambil semua paket wifi dari database
        $paketWifi = PaketWifi::all();

        // Menampilkan view dengan data paket wifi
        return view('user.paketwifi', compact('paketWifi'));
    }
    
    

    /**
     * Menyimpan pengajuan paket wifi oleh pengguna
     */
    public function storePengajuan(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'paket' => 'required|exists:paket_wifi,id_paket_wifi', // Validasi ID paket wifi yang dipilih
        ]);

        // Menyimpan data pengajuan
        Pengajuan::create([
            'users_id_user' => auth()->id(),  // ID pengguna yang sedang login
            'paket_wifi_id_paket_wifi' => $request->paket,  // ID paket wifi yang dipilih
            'status_pengajuan_id' => 4,  // Status pengajuan (Misalnya: 'Menunggu')
        ]);

        // Mengalihkan kembali dengan pesan sukses
        return redirect()->route('user.pengaduanwifi')->with('success', 'Pengajuan berhasil!');
    }

    /**
     * Menampilkan data pengajuan oleh pengguna
     */
    public function showPengajuan()
    {
        // Mengambil data pengajuan pengguna yang sedang login beserta relasi ke PaketWifi
        $pengajuan = Pengajuan::with('paketWifi')
            ->where('users_id_user', auth()->id()) // Mengambil pengajuan berdasarkan pengguna yang login
            ->get();

        // Menampilkan view dengan data pengajuan
        return view('user.pengaduanwifi', compact('pengajuan'));
    }

    /**
     * Menampilkan data user beserta pengajuan dan paket wifi terkait
     */
    public function showUserData($id)
    {
        // Mengambil data user berdasarkan ID
        $user = User::with(['pengajuan', 'pengajuan.paketWifi'])->findOrFail($id);

        // Menampilkan view dengan data pengguna
        return view('user.show', compact('user'));
    }
}

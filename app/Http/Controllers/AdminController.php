<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaketWifi;
use App\Models\Tagihan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        // Terapkan middleware pada semua metode di controller ini
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Menampilkan dashboard admin dengan data ringkasan
     */
    public function dashboard()
    {
        // Ambil 5 pembayaran terbaru dengan status 'Lunas'
        $recentPayments = Tagihan::with(['user', 'paketWifi', 'statusTagihan'])
            ->where('status_tagihan_id', 3)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Ambil 5 pengaduan terbaru
        $recentComplaints = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data untuk chart - top 5 pelanggan berdasarkan pembayaran
        $chartData = Tagihan::select('users_id_user', DB::raw('COUNT(*) as total'))
            ->where('status_tagihan_id', 3)
            ->groupBy('users_id_user')
            ->with('user')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('recentPayments', 'recentComplaints', 'chartData'));
    }

    /**
     * Menampilkan daftar pelanggan (non-admin)
     */
    public function index()
    {
        $pelanggan = User::whereNotIn('role', ['admin', 'superadmin', 'teknisi'])->get();

        return view('dashboard.pelanggan.index', compact('pelanggan'));
    }

    // ========== PAKET WIFI MANAGEMENT ==========

    /**
     * Menampilkan daftar paket wifi untuk admin
     */
    public function showPaketWifiAdmin()
    {
        $paketWifi = PaketWifi::all();

        return view('dashboard.datapaket.index', compact('paketWifi'));
    }

    /**
     * Menambah paket wifi baru oleh admin
     */
    public function storePaketWifi(Request $request)
    {
        $validatedData = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:20',
            'harga_bulanan' => 'required|numeric|min:0',
            'deskripsi_paket' => 'required|string',
        ]);

        PaketWifi::create($validatedData);

        return redirect()->route('admin.paketwifi.index')
            ->with('success', 'Paket Wi-Fi berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit paket wifi
     */
    public function editPaketWifi($id)
    {
        $paketWifi = PaketWifi::findOrFail($id);

        return view('dashboard.datapaket.edit', compact('paketWifi'));
    }

    /**
     * Update paket wifi oleh admin
     */
    public function updatePaketWifi(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:20',
            'harga_bulanan' => 'required|numeric|min:0',
            'deskripsi_paket' => 'required|string',
        ]);

        $paketWifi = PaketWifi::findOrFail($id);
        $paketWifi->update($validatedData);

        return redirect()->route('dashboard.datapaket.index')
            ->with('success', 'Paket Wi-Fi berhasil diperbarui!');
    }

    /**
     * Menghapus paket wifi oleh admin
     */
    public function deletePaketWifi($id)
    {
        $paketWifi = PaketWifi::findOrFail($id);
        $paketWifi->delete();

        return redirect()->route('dashboard.datapaket.index')
            ->with('success', 'Paket Wi-Fi berhasil dihapus!');
    }
}














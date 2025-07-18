<?php

namespace App\Http\Controllers;

use App\Models\Datapelanggan;
use App\Models\PaketWifi;
use App\Models\Tagihan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk user - menampilkan ringkasan data
     */
    public function index()
    {
        // Ambil ID pengguna yang login
        $userId = auth()->user()->id_user;

        // Mengambil data statistik
        $totalPaket = PaketWifi::count();
        $totalPelanggan = Datapelanggan::count();
        $belumDibayar = Tagihan::where('status', 'belum dibayar')->count();
        $lunas = Tagihan::where('status', 'lunas')->count();

        return view('user.dashboard', [
            'totalPaket' => $totalPaket,
            'totalPelanggan' => $totalPelanggan,
            'belumDibayar' => $belumDibayar,
            'lunas' => $lunas
        ]);
    }

    /**
     * Dashboard untuk superadmin - menampilkan data lengkap dengan chart
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

        return view('superadmin.dashboard', compact('recentPayments', 'recentComplaints', 'chartData'));
    }
}
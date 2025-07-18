<?php

namespace App\Http;

use Illuminate\Support\Facades\View;
use App\Models\PaketWifi;
use App\Models\User;
use App\Models\Tagihan;
use App\Models\DataPelanggan;

class Composer
{
    /**
     * Register data yang akan dibagikan ke view.
     *
     * @return void
     */
    public static function register()
    {
        // Daftar view yang memerlukan data
        View::composer([
            'dashboard.admin',
            'dashboard.datapaket.index',
            'dashboard.datapelanggan.index', 
            'dashboard.datapelanggan.create',
            'dashboard.datapelanggan.edit',
            'dashboard.datatagihan.create',
            'dashboard.datatagihan.index',
            'dashboard.pengajuan.index',
            'dashboard.pengaduan.index',
            'superadmin.laporankeuangan.index', // Tambahkan view keuangan
            'superadmin.dashboard',
            'superadmin.pengajuan.index',
            'superadmin.pengaduan.index',
            'superadmin.datapaket.index',
            'superadmin.datapelanggan.index',
            'superadmin.datatagihan.index',
            'superadmin.datatagihan.create',
            'superadmin.datapelanggan.edit',
            'superadmin.keuangan.index' // Tambahkan view keuangan superadmin
        ], function ($view) {
            // Mengambil total pengguna yang bukan superadmin, admin, atau teknisi
            $totalUsers = User::whereNotIn('role_id', [1, 4, 3])->count();

            // Mengambil total paket
            $totalPaket = PaketWifi::count();  

            // Mengambil jumlah tagihan yang belum dibayar
            $belumDibayar = Tagihan::where('status_tagihan_id', 1)->count();

            // Mengambil jumlah tagihan yang lunas
            $lunas = Tagihan::where('status_tagihan_id', 3)->count();

            // Data khusus untuk halaman keuangan
            $totalPendapatan = Tagihan::where('status_tagihan_id', 3)
                ->sum('jumlah_tagihan');
            
            $pendingPayment = Tagihan::where('status_tagihan_id', 2)
                ->sum('jumlah_tagihan');

            // Bagikan data ke view
            $view->with([
                'totalUsers' => $totalUsers,
                'totalPaket' => $totalPaket,
                'belumDibayar' => $belumDibayar,
                'lunas' => $lunas,
                'totalPendapatan' => $totalPendapatan,
                'pendingPayment' => $pendingPayment
            ]);
        });
    }
}
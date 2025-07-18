<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\DataPelanggan;
use App\Models\PaketWifi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    /**
     * Menampilkan dashboard laporan keuangan
     */
    public function index()
    {
        return view('superadmin.laporankeuangan.index');
    }

    /**
     * Mengambil data keuangan untuk request AJAX
     */
    public function getFinancialData(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfYear()->format('Y-m-d'));
        $paket = $request->input('paket');
        $status = $request->input('status');

        // Build query dengan filter
        $query = Tagihan::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($paket) {
            $query->where('paket_wifi_id_paket_wifi', $paket);
        }
        
        if ($status) {
            $query->where('status_tagihan_id', $status);
        }

        // Ambil data ringkasan
        $summary = $this->getSummary($query);
        
        // Ambil data chart
        $charts = $this->getChartData($startDate, $endDate, $paket, $status);
        
        // Ambil data transaksi
        $transactions = $this->getTransactions($query);
        
        // Ambil data paket
        $packages = $this->getPackagesData($startDate, $endDate);

        return response()->json([
            'summary' => $summary,
            'charts' => $charts,
            'transactions' => $transactions,
            'packages' => $packages
        ]);
    }

    /**
     * Mengambil data ringkasan keuangan
     */
    private function getSummary($query)
    {
        $baseQuery = clone $query;
        
        $totalRevenue = $baseQuery->sum('jumlah_tagihan');
        $paidAmount = $baseQuery->where('status_tagihan_id', 3)->sum('jumlah_tagihan');
        $pendingAmount = $baseQuery->where('status_tagihan_id', 2)->sum('jumlah_tagihan');
        $unpaidAmount = $baseQuery->where('status_tagihan_id', 1)->sum('jumlah_tagihan');

        return [
            'total_revenue' => $totalRevenue,
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'unpaid_amount' => $unpaidAmount
        ];
    }

    /**
     * Mengambil data untuk chart
     */
    private function getChartData($startDate, $endDate, $paket = null, $status = null)
    {
        // Data pendapatan bulanan
        $monthlyRevenue = DB::table('tagihan')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(jumlah_tagihan) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_tagihan_id', 3) // Hanya tagihan yang sudah dibayar
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyLabels = [];
        $monthlyAmounts = [];
        
        foreach ($monthlyRevenue as $data) {
            $monthlyLabels[] = Carbon::create($data->year, $data->month)->format('M Y');
            $monthlyAmounts[] = $data->total;
        }

        // Distribusi status tagihan
        $statusDistribution = [
            'paid' => Tagihan::whereBetween('created_at', [$startDate, $endDate])
                ->where('status_tagihan_id', 3)->sum('jumlah_tagihan'),
            'pending' => Tagihan::whereBetween('created_at', [$startDate, $endDate])
                ->where('status_tagihan_id', 2)->sum('jumlah_tagihan'),
            'unpaid' => Tagihan::whereBetween('created_at', [$startDate, $endDate])
                ->where('status_tagihan_id', 1)->sum('jumlah_tagihan')
        ];

        return [
            'monthly_revenue' => [
                'labels' => $monthlyLabels,
                'amounts' => $monthlyAmounts
            ],
            'status_distribution' => $statusDistribution
        ];
    }

    /**
     * Mengambil data transaksi
     */
    private function getTransactions($query)
    {
        return $query->with(['user', 'paketWifi'])
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(function($tagihan) {
                return [
                    'id' => $tagihan->id,
                    'order_id' => $tagihan->id,
                    'customer_name' => $tagihan->user->name ?? 'Unknown',
                    'package_name' => $tagihan->paketWifi->nama_paket ?? 'Unknown',
                    'amount' => $tagihan->jumlah_tagihan,
                    'status' => $this->mapStatus($tagihan->status_tagihan_id),
                    'payment_method' => $tagihan->metode_pembayaran ?? null,
                    'created_at' => $tagihan->created_at
                ];
            });
    }

    /**
     * Mengambil data paket dan pendapatannya
     */
    private function getPackagesData($startDate, $endDate)
    {
        return DB::table('paket_wifi')
            ->leftJoin('tagihan', 'paket_wifi.id_paket_wifi', '=', 'tagihan.paket_wifi_id_paket_wifi')
            ->select(
                'paket_wifi.nama_paket as package_name',
                DB::raw('COUNT(DISTINCT tagihan.users_id_user) as customer_count'),
                DB::raw('COALESCE(SUM(CASE WHEN tagihan.status_tagihan_id = 3 AND tagihan.created_at BETWEEN "' . $startDate . '" AND "' . $endDate . '" THEN tagihan.jumlah_tagihan END), 0) as total_revenue')
            )
            ->groupBy('paket_wifi.id_paket_wifi', 'paket_wifi.nama_paket')
            ->get();
    }

    /**
     * Mapping status ID ke string status
     */
    private function mapStatus($statusId)
    {
        $statusMap = [
            1 => 'pending',
            2 => 'pending', 
            3 => 'success'
        ];

        return $statusMap[$statusId] ?? 'pending';
    }

    /**
     * Mengambil detail transaksi berdasarkan ID
     */
    public function getTransaction($id)
    {
        $tagihan = Tagihan::with(['user', 'paketWifi'])->find($id);
        
        if (!$tagihan) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaction = [
            'id' => $tagihan->id,
            'order_id' => $tagihan->id,
            'customer_name' => $tagihan->user->name ?? 'Unknown',
            'package_name' => $tagihan->paketWifi->nama_paket ?? 'Unknown',
            'amount' => $tagihan->jumlah_tagihan,
            'status' => $this->mapStatus($tagihan->status_tagihan_id),
            'payment_method' => $tagihan->metode_pembayaran ?? null,
            'created_at' => $tagihan->created_at
        ];

        return response()->json(['transaction' => $transaction]);
    }

    /**
     * Menandai transaksi sebagai lunas
     */
    public function markAsPaid($id)
    {
        $tagihan = Tagihan::find($id);
        
        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan'
            ]);
        }

        $tagihan->status_tagihan_id = 3; // Set sebagai lunas
        $tagihan->tanggal_pembayaran = Carbon::now();
        $tagihan->save();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil ditandai sebagai lunas'
        ]);
    }

    /**
     * Export data ke Excel
     */
    public function exportExcel(Request $request)
    {
        // TODO: Implementasi export Excel
        // Bisa menggunakan Laravel Excel package
        return response()->json(['message' => 'Fitur export Excel belum diimplementasikan']);
    }

    /**
     * Export data ke PDF
     */
    public function exportPDF(Request $request)
    {
        // TODO: Implementasi export PDF
        // Bisa menggunakan package PDF seperti TCPDF atau DomPDF
        return response()->json(['message' => 'Fitur export PDF belum diimplementasikan']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaketWifi;
use App\Models\StatusTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKeuanganController extends Controller
{
    // Status tagihan constants berdasarkan database
    const STATUS_LUNAS = 1;
    const STATUS_BELUM_BAYAR = 2;
    const STATUS_SEBAGIAN = 3;
    const STATUS_DIBATALKAN = 4;
    const STATUS_TERLAMBAT = 5;

    /**
     * Display the financial report page
     */
    public function index()
    {
        $data = [
            'title' => 'Laporan Keuangan',
            'paketList' => $this->getPaketList(),
            'statusList' => $this->getStatusList(),
            'summaryCards' => $this->getSummaryCards(),
            'chartData' => $this->getChartData(),
            'agingReport' => $this->getAgingReport(),
        ];

        return view('superadmin.laporankeuangan.index', $data);
    }

    /**
     * Get filtered financial data via AJAX
     */
    public function getData(Request $request)
    {
        try {
            $dateRange = $request->input('date_range');
            $paketFilter = $request->input('paket');
            $statusFilter = $request->input('status');

            // Parse date range - handle both formats
            if (strpos($dateRange, ' - ') !== false) {
                $dates = explode(' - ', $dateRange);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
            } else {
                // Default to current month if no range provided
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }

            // Get filtered transactions from tagihan
            $transactions = $this->getFilteredTransactions($startDate, $endDate, $paketFilter, $statusFilter);
            
            // Get summary data
            $summary = $this->getFilteredSummary($startDate, $endDate, $paketFilter, $statusFilter);
            
            // Get paket summary
            $paketSummary = $this->getPaketSummary($startDate, $endDate, $statusFilter);

            // Get aging report
            $agingReport = $this->getAgingReport();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'transactions' => $transactions,
                    'summary' => $summary,
                    'paketSummary' => $paketSummary,
                    'agingReport' => $agingReport,
                    'chartData' => $this->getFilteredChartData($startDate, $endDate, $paketFilter, $statusFilter)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $dateRange = $request->input('date_range', Carbon::now()->format('d/m/Y') . ' - ' . Carbon::now()->format('d/m/Y'));
            $paketFilter = $request->input('paket');
            $statusFilter = $request->input('status');

            // Parse date range
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

            $transactions = $this->getFilteredTransactions($startDate, $endDate, $paketFilter, $statusFilter);
            $summary = $this->getFilteredSummary($startDate, $endDate, $paketFilter, $statusFilter);
            $paketSummary = $this->getPaketSummary($startDate, $endDate, $statusFilter);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header dengan styling
            $sheet->setCellValue('A1', 'LAPORAN KEUANGAN EVAN NETWORK');
            $sheet->setCellValue('A2', 'Periode: ' . $dateRange);
            $sheet->setCellValue('A3', 'Digenerate pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
            $sheet->mergeCells('A1:J1');
            $sheet->mergeCells('A2:J2');
            $sheet->mergeCells('A3:J3');

            // Summary section
            $sheet->setCellValue('A5', 'RINGKASAN KEUANGAN');
            $sheet->setCellValue('A6', 'Total Tagihan:');
            $sheet->setCellValue('B6', 'Rp ' . number_format($summary['total_tagihan'], 0, ',', '.'));
            $sheet->setCellValue('C6', 'Total Transaksi:');
            $sheet->setCellValue('D6', $summary['total_transaksi']);
            
            $sheet->setCellValue('A7', 'Tagihan Lunas:');
            $sheet->setCellValue('B7', 'Rp ' . number_format($summary['tagihan_lunas'], 0, ',', '.'));
            $sheet->setCellValue('C7', 'Persentase Bayar:');
            $sheet->setCellValue('D7', ($summary['total_tagihan'] > 0 ? round(($summary['tagihan_lunas'] / $summary['total_tagihan']) * 100, 1) : 0) . '%');
            
            $sheet->setCellValue('A8', 'Tagihan Belum Bayar:');
            $sheet->setCellValue('B8', 'Rp ' . number_format($summary['tagihan_belum_bayar'], 0, ',', '.'));
            $sheet->setCellValue('A9', 'Tagihan Sebagian:');
            $sheet->setCellValue('B9', 'Rp ' . number_format($summary['tagihan_sebagian'], 0, ',', '.'));

            // Package summary section
            if ($paketSummary->count() > 0) {
                $sheet->setCellValue('A11', 'RINGKASAN PER PAKET');
                $row = 12;
                foreach ($paketSummary as $paket) {
                    $sheet->setCellValue('A' . $row, $paket->nama_paket);
                    $sheet->setCellValue('B' . $row, $paket->jumlah_pelanggan . ' pelanggan');
                    $sheet->setCellValue('C' . $row, 'Rp ' . number_format($paket->total_tagihan, 0, ',', '.'));
                    $sheet->setCellValue('D' . $row, $paket->persentase . '%');
                    $row++;
                }
                $tableStart = $row + 1;
            } else {
                $tableStart = 11;
            }

            // Table Header
            $sheet->setCellValue('A' . $tableStart, 'No');
            $sheet->setCellValue('B' . $tableStart, 'Periode');
            $sheet->setCellValue('C' . $tableStart, 'Pelanggan');
            $sheet->setCellValue('D' . $tableStart, 'Paket');
            $sheet->setCellValue('E' . $tableStart, 'Jumlah Tagihan');
            $sheet->setCellValue('F' . $tableStart, 'Jatuh Tempo');
            $sheet->setCellValue('G' . $tableStart, 'Status');
            $sheet->setCellValue('H' . $tableStart, 'Tgl Bayar');
            $sheet->setCellValue('I' . $tableStart, 'Metode Bayar');
            $sheet->setCellValue('J' . $tableStart, 'Hari Terlambat');

            // Data
            $row = $tableStart + 1;
            foreach ($transactions as $index => $transaction) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('C' . $row, $transaction->nama_pelanggan);
                $sheet->setCellValue('D' . $row, $transaction->nama_paket);
                $sheet->setCellValue('E' . $row, 'Rp ' . number_format($transaction->jumlah_tagihan, 0, ',', '.'));
                $sheet->setCellValue('F' . $row, $transaction->jatuh_tempo ? Carbon::parse($transaction->jatuh_tempo)->format('d/m/Y') : '-');
                $sheet->setCellValue('G' . $row, $this->getStatusName($transaction->status_tagihan_id));
                $sheet->setCellValue('H' . $row, $transaction->tanggal_pembayaran ? Carbon::parse($transaction->tanggal_pembayaran)->format('d/m/Y') : '-');
                $sheet->setCellValue('I' . $row, $transaction->metode_pembayaran ?? '-');
                
                // Hitung hari terlambat
                $hariTerlambat = '';
                if ($transaction->status_tagihan_id == self::STATUS_BELUM_BAYAR && $transaction->jatuh_tempo) {
                    $hariTerlambat = Carbon::now()->diffInDays(Carbon::parse($transaction->jatuh_tempo), false);
                    $hariTerlambat = $hariTerlambat < 0 ? abs($hariTerlambat) . ' hari' : 'Belum terlambat';
                }
                $sheet->setCellValue('J' . $row, $hariTerlambat);
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'laporan-keuangan-' . Carbon::now()->format('Y-m-d-His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export data to PDF
     */
    public function exportPDF(Request $request)
    {
        try {
            $dateRange = $request->input('date_range', Carbon::now()->format('d/m/Y') . ' - ' . Carbon::now()->format('d/m/Y'));
            $paketFilter = $request->input('paket');
            $statusFilter = $request->input('status');

            // Parse date range
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

            $transactions = $this->getFilteredTransactions($startDate, $endDate, $paketFilter, $statusFilter);
            $summary = $this->getFilteredSummary($startDate, $endDate, $paketFilter, $statusFilter);
            $paketSummary = $this->getPaketSummary($startDate, $endDate, $statusFilter);
            $agingReport = $this->getAgingReport();

            $data = [
                'transactions' => $transactions,
                'summary' => $summary,
                'paketSummary' => $paketSummary,
                'agingReport' => $agingReport,
                'dateRange' => $dateRange,
                'filters' => [
                    'paket' => $paketFilter ? PaketWifi::find($paketFilter)?->nama_paket : 'Semua Paket',
                    'status' => $statusFilter ? $this->getStatusName($statusFilter) : 'Semua Status'
                ],
                'generatedAt' => Carbon::now()->format('d/m/Y H:i:s')
            ];

            $pdf = Pdf::loadView('superadmin.laporankeuangan.pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download('laporan-keuangan-' . Carbon::now()->format('Y-m-d-His') . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction details for modal
     */
    public function getTransactionDetail($id)
    {
        try {
            $transaction = DB::table('tagihan as t')
                ->join('users as u', 't.users_id_user', '=', 'u.id')
                ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
                ->leftJoin('pembayaran as p', 't.id_tagihan', '=', 'p.tagihan_id')
                ->select(
                    't.*',
                    'u.name as nama_pelanggan',
                    'u.email',
                    'u.phone as no_hp',
                    'u.address as alamat',
                    'pw.nama_paket',
                    'pw.harga_bulanan as harga',
                    'pw.kecepatan',
                    'p.tanggal_pembayaran',
                    'p.metode_pembayaran',
                    'p.midtrans_transaction_id',
                    'p.jumlah_bayar'
                )
                ->where('t.id_tagihan', $id)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tagihan tidak ditemukan'
                ], 404);
            }

            // Hitung informasi tambahan
            $transaction->status_name = $this->getStatusName($transaction->status_tagihan_id);
            $transaction->hari_terlambat = null;
            
            if ($transaction->status_tagihan_id == self::STATUS_BELUM_BAYAR && $transaction->jatuh_tempo) {
                $hariTerlambat = Carbon::now()->diffInDays(Carbon::parse($transaction->jatuh_tempo), false);
                $transaction->hari_terlambat = $hariTerlambat < 0 ? abs($hariTerlambat) : 0;
            }

            return response()->json([
                'status' => 'success',
                'data' => $transaction
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of available packages
     */
    private function getPaketList()
    {
        return PaketWifi::select('id_paket_wifi', 'nama_paket', 'harga_bulanan', 'kecepatan')
            ->orderBy('nama_paket')
            ->get();
    }

    /**
     * Get list of status options
     */
    private function getStatusList()
    {
        return [
            ['id' => self::STATUS_LUNAS, 'name' => 'Lunas'],
            ['id' => self::STATUS_BELUM_BAYAR, 'name' => 'Belum Bayar'],
            ['id' => self::STATUS_SEBAGIAN, 'name' => 'Sebagian'],
            ['id' => self::STATUS_DIBATALKAN, 'name' => 'Dibatalkan'],
            ['id' => self::STATUS_TERLAMBAT, 'name' => 'Terlambat'],
        ];
    }

    /**
     * Get status name by ID
     */
    private function getStatusName($statusId)
    {
        $statusMap = [
            self::STATUS_LUNAS => 'Lunas',
            self::STATUS_BELUM_BAYAR => 'Belum Bayar',
            self::STATUS_SEBAGIAN => 'Sebagian',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            self::STATUS_TERLAMBAT => 'Terlambat',
        ];

        return $statusMap[$statusId] ?? 'Unknown';
    }

    /**
     * Get summary cards data
     */
    private function getSummaryCards()
    {
        $currentMonth = Carbon::now();
        
        $summary = DB::table('tagihan as t')
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(jumlah_tagihan) as total_tagihan,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as tagihan_lunas,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as tagihan_belum_bayar,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as tagihan_sebagian,
                COUNT(CASE WHEN status_tagihan_id = ? THEN 1 END) as jumlah_belum_bayar
            ', [self::STATUS_LUNAS, self::STATUS_BELUM_BAYAR, self::STATUS_SEBAGIAN, self::STATUS_BELUM_BAYAR])
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->first();

        // Hitung tingkat pembayaran
        $tingkatPembayaran = $summary->total_tagihan > 0 ? 
            round(($summary->tagihan_lunas / $summary->total_tagihan) * 100, 1) : 0;

        return [
            'total_transaksi' => $summary->total_transaksi ?? 0,
            'total_tagihan' => $summary->total_tagihan ?? 0,
            'tagihan_lunas' => $summary->tagihan_lunas ?? 0,
            'tagihan_belum_bayar' => $summary->tagihan_belum_bayar ?? 0,
            'tagihan_sebagian' => $summary->tagihan_sebagian ?? 0,
            'jumlah_belum_bayar' => $summary->jumlah_belum_bayar ?? 0,
            'tingkat_pembayaran' => $tingkatPembayaran,
        ];
    }

    /**
     * Get chart data for current year
     */
    private function getChartData()
    {
        $currentYear = date('Y');
        
        $monthlyData = DB::table('tagihan as t')
            ->selectRaw('
                MONTH(created_at) as bulan,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as pendapatan,
                SUM(jumlah_tagihan) as total_tagihan,
                COUNT(*) as total_transaksi
            ', [self::STATUS_LUNAS])
            ->whereYear('created_at', $currentYear)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartData = [];
        $totalTagihan = [];
        $totalTransaksi = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        // Initialize arrays
        for ($i = 0; $i < 12; $i++) {
            $chartData[$i] = 0;
            $totalTagihan[$i] = 0;
            $totalTransaksi[$i] = 0;
        }
        
        foreach ($monthlyData as $data) {
            $chartData[$data->bulan - 1] = $data->pendapatan;
            $totalTagihan[$data->bulan - 1] = $data->total_tagihan;
            $totalTransaksi[$data->bulan - 1] = $data->total_transaksi;
        }

        return [
            'labels' => $months,
            'pendapatan' => $chartData,
            'total_tagihan' => $totalTagihan,
            'total_transaksi' => $totalTransaksi
        ];
    }

    /**
     * Get filtered transactions from tagihan
     */
    private function getFilteredTransactions($startDate, $endDate, $paketFilter = null, $statusFilter = null)
    {
        $query = DB::table('tagihan as t')
            ->join('users as u', 't.users_id_user', '=', 'u.id')
            ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
            ->leftJoin('pembayaran as p', 't.id_tagihan', '=', 'p.tagihan_id')
            ->select(
                't.id_tagihan',
                't.jumlah_tagihan',
                't.jatuh_tempo',
                't.status_tagihan_id',
                't.created_at',
                't.updated_at',
                'u.name as nama_pelanggan',
                'u.email',
                'pw.nama_paket',
                'pw.kecepatan',
                'p.tanggal_pembayaran',
                'p.metode_pembayaran',
                'p.jumlah_bayar'
            )
            ->whereBetween('t.created_at', [$startDate, $endDate]);

        if ($paketFilter && $paketFilter != 'all') {
            $query->where('t.paket_wifi_id_paket_wifi', $paketFilter);
        }

        if ($statusFilter && $statusFilter != 'all') {
            $query->where('t.status_tagihan_id', $statusFilter);
        }

        return $query->orderBy('t.created_at', 'desc')->get();
    }

    /**
     * Get filtered summary data
     */
    private function getFilteredSummary($startDate, $endDate, $paketFilter = null, $statusFilter = null)
    {
        $query = DB::table('tagihan as t')
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(jumlah_tagihan) as total_tagihan,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as tagihan_lunas,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as tagihan_belum_bayar,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as tagihan_sebagian,
                COUNT(DISTINCT users_id_user) as total_pelanggan
            ', [self::STATUS_LUNAS, self::STATUS_BELUM_BAYAR, self::STATUS_SEBAGIAN])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($paketFilter && $paketFilter != 'all') {
            $query->where('paket_wifi_id_paket_wifi', $paketFilter);
        }

        if ($statusFilter && $statusFilter != 'all') {
            $query->where('status_tagihan_id', $statusFilter);
        }

        $result = $query->first();

        return [
            'total_transaksi' => $result->total_transaksi ?? 0,
            'total_tagihan' => $result->total_tagihan ?? 0,
            'tagihan_lunas' => $result->tagihan_lunas ?? 0,
            'tagihan_belum_bayar' => $result->tagihan_belum_bayar ?? 0,
            'tagihan_sebagian' => $result->tagihan_sebagian ?? 0,
            'total_pelanggan' => $result->total_pelanggan ?? 0,
        ];
    }

    /**
     * Get package summary data
     */
    private function getPaketSummary($startDate, $endDate, $statusFilter = null)
    {
        $query = DB::table('tagihan as t')
            ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
            ->selectRaw('
                pw.nama_paket,
                pw.harga_bulanan,
                pw.kecepatan,
                COUNT(DISTINCT t.users_id_user) as jumlah_pelanggan,
                COUNT(*) as total_transaksi,
                SUM(t.jumlah_tagihan) as total_tagihan,
                AVG(t.jumlah_tagihan) as rata_rata_tagihan,
                SUM(CASE WHEN t.status_tagihan_id = ? THEN t.jumlah_tagihan ELSE 0 END) as total_terbayar
            ', [self::STATUS_LUNAS])
            ->whereBetween('t.created_at', [$startDate, $endDate]);

        if ($statusFilter && $statusFilter != 'all') {
            $query->where('t.status_tagihan_id', $statusFilter);
        }

        $results = $query->groupBy('t.paket_wifi_id_paket_wifi', 'pw.nama_paket', 'pw.harga_bulanan', 'pw.kecepatan')
            ->orderBy('total_tagihan', 'desc')
            ->get();

        // Calculate percentages
        $totalRevenue = $results->sum('total_tagihan');
        foreach ($results as $result) {
            $result->persentase = $totalRevenue > 0 ? 
                round(($result->total_tagihan / $totalRevenue) * 100, 1) : 0;
            $result->persentase_terbayar = $result->total_tagihan > 0 ? 
                round(($result->total_terbayar / $result->total_tagihan) * 100, 1) : 0;
        }

        return $results;
    }

    /**
     * Get filtered chart data
     */
    private function getFilteredChartData($startDate, $endDate, $paketFilter = null, $statusFilter = null)
    {
        $query = DB::table('tagihan as t')
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as periode,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as pendapatan,
                SUM(jumlah_tagihan) as total_tagihan,
                COUNT(*) as total_transaksi
            ', [self::STATUS_LUNAS])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($paketFilter && $paketFilter != 'all') {
            $query->where('paket_wifi_id_paket_wifi', $paketFilter);
        }

        if ($statusFilter && $statusFilter != 'all') {
            $query->where('status_tagihan_id', $statusFilter);
        }

        $results = $query->groupBy('periode')
            ->orderBy('periode')
            ->get();

        $chartData = [];
        $labels = [];
        $tagihanData = [];
        $transaksiData = [];
        
        foreach ($results as $result) {
            $labels[] = Carbon::createFromFormat('Y-m', $result->periode)->format('M Y');
            $chartData[] = $result->pendapatan;
            $tagihanData[] = $result->total_tagihan;
            $transaksiData[] = $result->total_transaksi;
        }

        return [
            'labels' => $labels,
            'pendapatan' => $chartData,
            'total_tagihan' => $tagihanData,
            'total_transaksi' => $transaksiData
        ];
    }

    /**
     * Get aging report (laporan umur piutang)
     */
    public function getAgingReport()
    {
        $today = Carbon::now();
        
        $aging = DB::table('tagihan as t')
            ->join('users as u', 't.users_id_user', '=', 'u.id_user')
            ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
            ->select(
                't.id_tagihan',
                
                't.jumlah_tagihan',
                't.jatuh_tempo',
                'u.nama as nama_pelanggan',
                'u.no_hp as no_hp',
                'pw.nama_paket',
                DB::raw('DATEDIFF(NOW(), t.jatuh_tempo) as hari_terlambat')
            )
            ->where('t.status_tagihan_id', self::STATUS_BELUM_BAYAR)
            ->whereDate('t.jatuh_tempo', '<', $today)
            ->orderBy('hari_terlambat', 'desc')
            ->limit(20) // Limit untuk performa
            ->get();

        return $aging;
    }

    /**
     * Get revenue projection based on active customers
     */
    public function getRevenueProjection($months = 3)
    {
        $projection = DB::table('tagihan as t')
            ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
            ->join('users as u', 't.users_id_user', '=', 'u.id')
            ->selectRaw('
                pw.nama_paket,
                pw.harga_bulanan,
                COUNT(DISTINCT t.users_id_user) as pelanggan_aktif,
                AVG(t.jumlah_tagihan) as rata_rata_tagihan,
                SUM(CASE WHEN t.status_tagihan_id = ? THEN 1 ELSE 0 END) as tingkat_pembayaran_count,
                COUNT(*) as total_tagihan_count,
                COUNT(DISTINCT t.users_id_user) * pw.harga_bulanan * ? as proyeksi_pendapatan_nominal,
                COUNT(DISTINCT t.users_id_user) * AVG(t.jumlah_tagihan) * ? as proyeksi_pendapatan_rata_rata
            ', [self::STATUS_LUNAS, $months, $months])
            ->where('t.status_tagihan_id', '!=', self::STATUS_DIBATALKAN)
            ->where('u.status', 'aktif') // Asumsi ada kolom status di tabel users
            ->whereMonth('t.created_at', '>=', Carbon::now()->subMonths(2)->month) // Data 2 bulan terakhir
            ->groupBy('t.paket_wifi_id_paket_wifi', 'pw.nama_paket', 'pw.harga_bulanan')
            ->orderBy('proyeksi_pendapatan_nominal', 'desc')
            ->get();

        // Hitung tingkat pembayaran untuk setiap paket
        foreach ($projection as $item) {
            $item->tingkat_pembayaran = $item->total_tagihan_count > 0 ? 
                round(($item->tingkat_pembayaran_count / $item->total_tagihan_count) * 100, 1) : 0;
        }

        return $projection;
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        // Current month stats
        $currentStats = DB::table('tagihan as t')
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(jumlah_tagihan) as total_tagihan,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as pendapatan,
                COUNT(DISTINCT users_id_user) as pelanggan_aktif
            ', [self::STATUS_LUNAS])
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->first();

        // Last month stats for comparison
        $lastStats = DB::table('tagihan as t')
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(jumlah_tagihan) as total_tagihan,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as pendapatan,
                COUNT(DISTINCT users_id_user) as pelanggan_aktif
            ', [self::STATUS_LUNAS])
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->first();

        // Calculate growth percentages
        $stats = [
            'current' => [
                'total_transaksi' => $currentStats->total_transaksi ?? 0,
                'total_tagihan' => $currentStats->total_tagihan ?? 0,
                'pendapatan' => $currentStats->pendapatan ?? 0,
                'pelanggan_aktif' => $currentStats->pelanggan_aktif ?? 0,
            ],
            'growth' => [
                'transaksi' => $this->calculateGrowth($lastStats->total_transaksi ?? 0, $currentStats->total_transaksi ?? 0),
                'tagihan' => $this->calculateGrowth($lastStats->total_tagihan ?? 0, $currentStats->total_tagihan ?? 0),
                'pendapatan' => $this->calculateGrowth($lastStats->pendapatan ?? 0, $currentStats->pendapatan ?? 0),
                'pelanggan' => $this->calculateGrowth($lastStats->pelanggan_aktif ?? 0, $currentStats->pelanggan_aktif ?? 0),
            ]
        ];

        return $stats;
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowth($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }

    /**
     * Get top performing packages
     */
    public function getTopPackages($limit = 5)
    {
        $currentMonth = Carbon::now();
        
        return DB::table('tagihan as t')
            ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
            ->selectRaw('
                pw.nama_paket,
                pw.harga_bulanan,
                pw.kecepatan,
                COUNT(*) as total_transaksi,
                COUNT(DISTINCT t.users_id_user) as jumlah_pelanggan,
                SUM(t.jumlah_tagihan) as total_pendapatan,
                SUM(CASE WHEN t.status_tagihan_id = ? THEN t.jumlah_tagihan ELSE 0 END) as pendapatan_terbayar
            ', [self::STATUS_LUNAS])
            ->whereMonth('t.created_at', $currentMonth->month)
            ->whereYear('t.created_at', $currentMonth->year)
            ->groupBy('t.paket_wifi_id_paket_wifi', 'pw.nama_paket', 'pw.harga_bulanan', 'pw.kecepatan')
            ->orderBy('pendapatan_terbayar', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get payment method statistics
     */
    public function getPaymentMethodStats($startDate = null, $endDate = null)
    {
        if (!$startDate) $startDate = Carbon::now()->startOfMonth();
        if (!$endDate) $endDate = Carbon::now()->endOfMonth();
        
        return DB::table('pembayaran as p')
            ->join('tagihan as t', 'p.tagihan_id', '=', 't.id_tagihan')
            ->selectRaw('
                p.metode_pembayaran,
                COUNT(*) as jumlah_transaksi,
                SUM(p.jumlah_bayar) as total_pembayaran,
                AVG(p.jumlah_bayar) as rata_rata_pembayaran
            ')
            ->whereBetween('p.tanggal_pembayaran', [$startDate, $endDate])
            ->whereNotNull('p.metode_pembayaran')
            ->groupBy('p.metode_pembayaran')
            ->orderBy('total_pembayaran', 'desc')
            ->get();
    }

    /**
     * Get overdue bills summary
     */
    public function getOverdueBills()
    {
        $today = Carbon::now();
        
        return DB::table('tagihan as t')
            ->join('users as u', 't.users_id_user', '=', 'u.id')
            ->join('paket_wifi as pw', 't.paket_wifi_id_paket_wifi', '=', 'pw.id_paket_wifi')
            ->selectRaw('
                COUNT(*) as total_overdue,
                SUM(t.jumlah_tagihan) as total_amount,
                AVG(DATEDIFF(NOW(), t.jatuh_tempo)) as avg_days_overdue,
                MAX(DATEDIFF(NOW(), t.jatuh_tempo)) as max_days_overdue
            ')
            ->where('t.status_tagihan_id', self::STATUS_BELUM_BAYAR)
            ->whereDate('t.jatuh_tempo', '<', $today)
            ->first();
    }

    /**
     * Get monthly trend data
     */
    public function getMonthlyTrend($months = 12)
    {
        $startDate = Carbon::now()->subMonths($months)->startOfMonth();
        
        return DB::table('tagihan as t')
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as periode,
                COUNT(*) as total_transaksi,
                COUNT(DISTINCT users_id_user) as pelanggan_unik,
                SUM(jumlah_tagihan) as total_tagihan,
                SUM(CASE WHEN status_tagihan_id = ? THEN jumlah_tagihan ELSE 0 END) as pendapatan,
                AVG(jumlah_tagihan) as rata_rata_tagihan
            ', [self::STATUS_LUNAS])
            ->where('created_at', '>=', $startDate)
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();
    }

    /**
     * Export aging report to Excel
     */
    public function exportAgingReport()
    {
        try {
            $agingData = $this->getAgingReport();
            $overdueSummary = $this->getOverdueBills();
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'LAPORAN UMUR PIUTANG - EVAN NETWORK');
            $sheet->setCellValue('A2', 'Digenerate pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
            $sheet->mergeCells('A1:H1');
            $sheet->mergeCells('A2:H2');

            // Summary
            $sheet->setCellValue('A4', 'RINGKASAN TUNGGAKAN');
            $sheet->setCellValue('A5', 'Total Tagihan Terlambat:');
            $sheet->setCellValue('B5', $overdueSummary->total_overdue ?? 0);
            $sheet->setCellValue('C5', 'Total Jumlah:');
            $sheet->setCellValue('D5', 'Rp ' . number_format($overdueSummary->total_amount ?? 0, 0, ',', '.'));
            $sheet->setCellValue('A6', 'Rata-rata Hari Terlambat:');
            $sheet->setCellValue('B6', round($overdueSummary->avg_days_overdue ?? 0, 0) . ' hari');
            $sheet->setCellValue('C6', 'Maksimal Hari Terlambat:');
            $sheet->setCellValue('D6', ($overdueSummary->max_days_overdue ?? 0) . ' hari');

            // Table Header
            $sheet->setCellValue('A8', 'No');
            $sheet->setCellValue('B8', 'Pelanggan');
            $sheet->setCellValue('C8', 'No. HP');
            $sheet->setCellValue('D8', 'Paket');
            $sheet->setCellValue('E8', 'Periode');
            $sheet->setCellValue('F8', 'Jumlah Tagihan');
            $sheet->setCellValue('G8', 'Jatuh Tempo');
            $sheet->setCellValue('H8', 'Hari Terlambat');

            // Data
            $row = 9;
            foreach ($agingData as $index => $item) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $item->nama_pelanggan);
                $sheet->setCellValue('C' . $row, $item->no_hp ?? '-');
                $sheet->setCellValue('D' . $row, $item->nama_paket);
                $sheet->setCellValue('F' . $row, 'Rp ' . number_format($item->jumlah_tagihan, 0, ',', '.'));
                $sheet->setCellValue('G' . $row, Carbon::parse($item->jatuh_tempo)->format('d/m/Y'));
                $sheet->setCellValue('H' . $row, $item->hari_terlambat . ' hari');
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'laporan-umur-piutang-' . Carbon::now()->format('Y-m-d-His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export laporan umur piutang: ' . $e->getMessage());
        }
    }

    /**
     * Mark bills as overdue (untuk dijadwalkan via cron job)
     */
    public function markOverdueBills()
    {
        $today = Carbon::now()->startOfDay();
        
        $updated = DB::table('tagihan')
            ->where('status_tagihan_id', self::STATUS_BELUM_BAYAR)
            ->whereDate('jatuh_tempo', '<', $today)
            ->update([
                'status_tagihan_id' => self::STATUS_TERLAMBAT,
                'updated_at' => Carbon::now()
            ]);

        return $updated;
    }

    /**
     * Get collection performance metrics
     */
    public function getCollectionMetrics($startDate = null, $endDate = null)
    {
        if (!$startDate) $startDate = Carbon::now()->startOfMonth();
        if (!$endDate) $endDate = Carbon::now()->endOfMonth();
        
        $metrics = DB::table('tagihan as t')
            ->leftJoin('pembayaran as p', 't.id_tagihan', '=', 'p.tagihan_id')
            ->selectRaw('
                COUNT(*) as total_tagihan,
                SUM(t.jumlah_tagihan) as total_amount,
                COUNT(CASE WHEN t.status_tagihan_id = ? THEN 1 END) as paid_count,
                SUM(CASE WHEN t.status_tagihan_id = ? THEN t.jumlah_tagihan ELSE 0 END) as paid_amount,
                AVG(CASE 
                    WHEN p.tanggal_pembayaran IS NOT NULL AND t.jatuh_tempo IS NOT NULL 
                    THEN DATEDIFF(p.tanggal_pembayaran, t.jatuh_tempo) 
                END) as avg_payment_delay,
                COUNT(CASE 
                    WHEN p.tanggal_pembayaran IS NOT NULL AND t.jatuh_tempo IS NOT NULL 
                    AND p.tanggal_pembayaran <= t.jatuh_tempo 
                    THEN 1 
                END) as on_time_payments
            ', [self::STATUS_LUNAS, self::STATUS_LUNAS])
            ->whereBetween('t.created_at', [$startDate, $endDate])
            ->first();

        $collectionRate = $metrics->total_amount > 0 ? 
            round(($metrics->paid_amount / $metrics->total_amount) * 100, 2) : 0;
            
        $onTimeRate = $metrics->paid_count > 0 ? 
            round(($metrics->on_time_payments / $metrics->paid_count) * 100, 2) : 0;

        return [
            'total_tagihan' => $metrics->total_tagihan,
            'total_amount' => $metrics->total_amount,
            'paid_count' => $metrics->paid_count,
            'paid_amount' => $metrics->paid_amount,
            'collection_rate' => $collectionRate,
            'on_time_rate' => $onTimeRate,
            'avg_payment_delay' => round($metrics->avg_payment_delay ?? 0, 1),
        ];
    }
}
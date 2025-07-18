<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;
use Carbon\Carbon;

class UserTagihanController extends Controller
{
    /**
     * Display tagihan untuk user yang sedang login
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        
        // Ambil semua tagihan milik user dengan relasi yang diperlukan
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan'])
            ->where('user_id', $user->id)
            ->orderBy('jatuh_tempo', 'desc')
            ->get();

        // Hitung statistik tagihan
        $totalTagihan = $tagihan->count();
        $tagihanBelumBayar = $tagihan->where('statusTagihan.status', '!=', 'lunas')->count();
        $tagihanTerlambat = $tagihan->filter(function ($item) {
            return Carbon::parse($item->jatuh_tempo)->isPast() && 
                   ($item->statusTagihan->status ?? 'belum_bayar') != 'lunas';
        })->count();
        $totalNominal = $tagihan->where('statusTagihan.status', '!=', 'lunas')->sum('jumlah_tagihan');

        return view('user.tagihan.index', compact(
            'tagihan', 
            'totalTagihan', 
            'tagihanBelumBayar', 
            'tagihanTerlambat', 
            'totalNominal'
        ));
    }

    /**
     * Show detail tagihan
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan'])
            ->where('user_id', $user->id)
            ->where('id_tagihan', $id)
            ->firstOrFail();

        return view('user.tagihan.show', compact('tagihan'));
    }

    /**
     * Halaman pembayaran
     */
    public function payment()
    {
        $user = Auth::user();
        
        // Ambil tagihan yang belum dibayar
        $tagihan = Tagihan::with(['user', 'paketWifi', 'statusTagihan'])
            ->where('user_id', $user->id)
            ->whereHas('statusTagihan', function($query) {
                $query->where('status', '!=', 'lunas');
            })
            ->orderBy('jatuh_tempo', 'asc')
            ->get();

        // Transform data untuk payment
        $tagihanPayment = $tagihan->map(function($item) {
            return [
                'invoice_number' => $item->id_tagihan,
                'jatuh_tempo' => Carbon::parse($item->jatuh_tempo)->format('d/m/Y'),
                'amount' => $item->jumlah_tagihan + ($item->denda ?? 0),
                'status' => $item->statusTagihan->status == 'lunas' ? 'PAID' : 'UNPAID',
                'paket' => $item->paketWifi->nama_paket ?? 'N/A',
                'periode' => Carbon::parse($item->periode_tagihan ?? $item->jatuh_tempo)->format('M Y')
            ];
        });

        return view('user.tagihan.payment', compact('tagihan', 'tagihanPayment'));
    }

    /**
     * Create Midtrans transaction
     */
    public function createTransaction(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        // Verifikasi tagihan milik user
        $tagihan = Tagihan::where('user_id', $user->id)
            ->where('id_tagihan', $request->invoice_number)
            ->firstOrFail();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $request->invoice_number . '-' . time(),
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $user->nama,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $user->no_hp ?? '',
            ],
            'item_details' => [
                [
                    'id' => $tagihan->paketWifi->id ?? 1,
                    'price' => $request->amount,
                    'quantity' => 1,
                    'name' => 'Tagihan WiFi - ' . ($tagihan->paketWifi->nama_paket ?? 'Paket WiFi'),
                ]
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json(['snapToken' => $snapToken]);
    }

    /**
     * Handle Midtrans callback
     */
    public function handleCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Extract invoice number from order_id
                $invoiceNumber = explode('-', $request->order_id)[0];
                
                // Update status tagihan
                $tagihan = Tagihan::where('id_tagihan', $invoiceNumber)->first();
                if ($tagihan) {
                    // Update status menjadi lunas
                    $tagihan->statusTagihan()->update(['status' => 'lunas']);
                    
                    // Simpan log pembayaran jika diperlukan
                    // PaymentLog::create([...]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    
}
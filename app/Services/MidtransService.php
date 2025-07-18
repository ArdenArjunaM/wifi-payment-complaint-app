<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;
use App\Models\Tagihan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
        
        // Log konfigurasi untuk debugging
        Log::info('Midtrans Configuration:', [
            'server_key' => Config::$serverKey ? 'SET' : 'NOT SET',
            'client_key' => Config::$clientKey ? 'SET' : 'NOT SET',
            'is_production' => Config::$isProduction,
            'is_sanitized' => Config::$isSanitized,
            'is_3ds' => Config::$is3ds,
        ]);
        
        // Validasi konfigurasi
        if (empty(Config::$serverKey) || empty(Config::$clientKey)) {
            Log::error('Midtrans configuration is incomplete', [
                'server_key_empty' => empty(Config::$serverKey),
                'client_key_empty' => empty(Config::$clientKey),
            ]);
            throw new \Exception('Midtrans configuration is incomplete. Please check your .env file.');
        }
    }

    /**
     * Create transaction for tagihan
     */
    public function createTransaction(Tagihan $tagihan)
    {
        try {
            // Hitung total dengan denda jika terlambat
            $totalAmount = $tagihan->jumlah_tagihan;
            $denda = 0;
            
            if (Carbon::parse($tagihan->jatuh_tempo)->isPast() && $tagihan->status_tagihan_id != 3) {
                $denda = 5000;
                $totalAmount += $denda;
            }

            // Generate unique order ID
            $orderId = 'PAY-' . $tagihan->id_tagihan . '-' . time();

            // Siapkan parameter untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $tagihan->user->name ?? 'Customer',
                    'email' => $tagihan->user->email ?? 'customer@email.com',
                    'phone' => $tagihan->user->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'WIFI-' . $tagihan->paketWifi->id_paket_wifi,
                        'price' => (int) $tagihan->jumlah_tagihan,
                        'quantity' => 1,
                        'name' => $tagihan->paketWifi->nama_paket . ' - ' . Carbon::parse($tagihan->tanggal_tagihan)->format('M Y'),
                    ]
                ],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'error' => route('payment.error'),
                    'pending' => route('payment.pending'),
                ]
            ];

            // Tambahkan denda jika ada
            if ($denda > 0) {
                $params['item_details'][] = [
                    'id' => 'DENDA-' . $tagihan->id_tagihan,
                    'price' => (int) $denda,
                    'quantity' => 1,
                    'name' => 'Denda Keterlambatan',
                ];
            }

            // Log parameter untuk debugging
            Log::info('Midtrans payment params:', [
                'order_id' => $orderId,
                'gross_amount' => $totalAmount,
                'environment' => Config::$isProduction ? 'production' : 'sandbox',
                'customer' => $params['customer_details'],
            ]);

            // Cek apakah sudah ada payment dengan status pending
            $existingPayment = Payment::where('tagihan_id', $tagihan->id_tagihan)
                                    ->where('status', 'pending')
                                    ->first();

            if ($existingPayment) {
                // Update existing payment
                $payment = $existingPayment;
                $payment->update([
                    'order_id' => $orderId,
                    'amount' => $totalAmount,
                    'denda' => $denda,
                ]);
            } else {
                // Simpan data pembayaran baru
                $payment = Payment::create([
                    'tagihan_id' => $tagihan->id_tagihan,
                    'order_id' => $orderId,
                    'amount' => $totalAmount,
                    'denda' => $denda,
                    'status' => 'pending',
                    'payment_method' => null,
                    'transaction_id' => null,
                ]);
            }

            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap token generated successfully', [
                'order_id' => $orderId,
                'payment_id' => $payment->id
            ]);

            return [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'payment_id' => $payment->id
            ];

        } catch (\Exception $e) {
            Log::error('Error creating Midtrans transaction:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tagihan_id' => $tagihan->id_tagihan ?? null,
            ]);
            throw new \Exception('Gagal membuat transaksi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(array $notificationData)
    {
        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $type,
                'fraud_status' => $fraud,
                'gross_amount' => $notification->gross_amount ?? null,
            ]);

            // Cari payment berdasarkan order_id
            $payment = Payment::where('order_id', $orderId)->first();
            
            if (!$payment) {
                Log::error('Payment not found for order_id: ' . $orderId);
                return false;
            }

            // Handle berdasarkan status transaksi
            if ($transactionStatus == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->update(['status' => 'challenge']);
                    } else {
                        $this->updatePaymentSuccess($payment, $notification);
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->updatePaymentSuccess($payment, $notification);
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['status' => 'pending']);
            } elseif ($transactionStatus == 'deny') {
                $payment->update(['status' => 'denied']);
            } elseif ($transactionStatus == 'expire') {
                $payment->update(['status' => 'expired']);
            } elseif ($transactionStatus == 'cancel') {
                $payment->update(['status' => 'cancelled']);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Notification handling error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'notification_data' => $notificationData
            ]);
            return false;
        }
    }

    /**
     * Check transaction status from Midtrans
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            Log::info('Transaction status checked:', [
                'order_id' => $orderId,
                'status' => $status->transaction_status ?? 'unknown'
            ]);
            return $status;
        } catch (\Exception $e) {
            Log::error('Error checking transaction status: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return null;
        }
    }

    /**
     * Update payment success
     */
    private function updatePaymentSuccess($payment, $notification)
    {
        DB::beginTransaction();
        try {
            // Update payment
            $payment->update([
                'status' => 'success',
                'payment_method' => $notification->payment_type,
                'transaction_id' => $notification->transaction_id,
                'paid_at' => now(),
            ]);

            // Update tagihan
            $tagihan = Tagihan::find($payment->tagihan_id);
            if ($tagihan) {
                $tagihan->update([
                    'status_tagihan_id' => 3, // Status: Sudah Dibayar
                    'tanggal_bayar' => now(),
                ]);
            }

            DB::commit();
            Log::info('Payment updated successfully', [
                'order_id' => $payment->order_id,
                'payment_id' => $payment->id,
                'tagihan_id' => $tagihan->id_tagihan ?? null
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating payment success: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Test connection to Midtrans
     */
    public function testConnection()
    {
        try {
            // Test dengan order_id dummy
            $testOrderId = 'TEST-' . time();
            
            // Ini akan menghasilkan error karena order tidak ada, 
            // tapi jika dapat response berarti koneksi OK
            Transaction::status($testOrderId);
            
            return true;
        } catch (\Exception $e) {
            // Jika error 404 (order not found), berarti koneksi OK
            if (strpos($e->getMessage(), '404') !== false) {
                return true;
            }
            
            Log::error('Midtrans connection test failed: ' . $e->getMessage());
            return false;
        }
    }

        public function getTransactionStatus($orderId)
    {
        $serverKey = config('midtrans.server_key');
        $url = config('midtrans.is_production') 
            ? "https://api.midtrans.com/v2/{$orderId}/status"
            : "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

        // Implementation untuk get status dari Midtrans API
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use App\Models\Tagihan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        try {
            $result = $this->midtransService->handleNotification($request->all());
            
            if ($result) {
                return response('OK', 200);
            } else {
                return response('FAILED', 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment Notification Error: ' . $e->getMessage());
            return response('ERROR', 500);
        }
    }

    

    /**
     * Payment finish callback
     */
    public function paymentFinish(Request $request)
    {
        $orderId = $request->input('order_id');
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && $payment->tagihan) {
            // Update status tagihan menjadi lunas (3)
            $this->updateTagihanStatus($payment->tagihan, 'success');
            
            return redirect()->route('user.tagihan')->with('success', 'Pembayaran berhasil diproses!');
        }

        return redirect()->route('user.tagihan')->with('error', 'Pembayaran tidak ditemukan');
    }

    /**
     * Payment unfinish callback
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->input('order_id');
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && $payment->tagihan) {
            // Status tetap belum dibayar (1)
            $this->updateTagihanStatus($payment->tagihan, 'unfinish');
        }

        return redirect()->route('user.tagihan')->with('info', 'Pembayaran belum selesai');
    }

    /**
     * Payment error callback
     */
    public function error(Request $request)
    {
        $orderId = $request->input('order_id');
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && $payment->tagihan) {
            // Status tetap belum dibayar (1)
            $this->updateTagihanStatus($payment->tagihan, 'error');
        }

        return redirect()->route('user.tagihan')->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

    /**
     * Payment pending callback
     */
    public function pending(Request $request)
    {
        $orderId = $request->input('order_id');
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && $payment->tagihan) {
            // Status tetap belum dibayar (1) - tidak ada status processing lagi
            $this->updateTagihanStatus($payment->tagihan, 'pending');
        }

        return redirect()->route('user.tagihan')->with('info', 'Pembayaran sedang diproses.');
    }

    /**
     * Show payment page
     */
    public function show($tagihanId)
    {
        $tagihan = Tagihan::with('paketWifi', 'user')->findOrFail($tagihanId);

        // Check if tagihan is already paid (status 3 = Lunas)
        if ($tagihan->status_tagihan_id == 3) {
            return redirect()->route('user.tagihan')->with('info', 'Tagihan sudah dibayar');
        }

        return view('payment.show', compact('tagihan'));
    }

    /**
     * Process payment
     */
    public function process(Request $request, $tagihanId)
    {
        $tagihan = Tagihan::with('paketWifi', 'user')->findOrFail($tagihanId);

        // Check if tagihan is already paid (status 3 = Lunas)
        if ($tagihan->status_tagihan_id == 3) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan sudah dibayar'
            ], 400);
        }

        try {
            $payment = Payment::where('tagihan_id', $tagihan->id_tagihan)
                             ->whereIn('status', ['pending', 'challenge'])
                             ->first();

            if (!$payment || !$payment->order_id) {
                // Create new transaction if not exists
                $paymentData = $this->midtransService->createTransaction($tagihan);
                
                // Status tagihan tetap belum dibayar (1) sampai payment berhasil
            } else {
                // Use existing payment data
                $paymentData = [
                    'order_id' => $payment->order_id
                ];
            }

            return response()->json([
                'success' => true,
                'payment_data' => $paymentData
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Process Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create payment (legacy method for backward compatibility)
     */
    public function createPayment(Request $request)
    {
        try {
            $tagihan = Tagihan::with('paketWifi', 'user')->findOrFail($request->tagihan_id);
            
            // Check if tagihan is already paid (status 3 = Lunas)
            if ($tagihan->status_tagihan_id == 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan sudah dibayar'
                ]);
            }

            $paymentData = $this->midtransService->createTransaction($tagihan);

            // Status tagihan tetap belum dibayar (1) sampai payment berhasil

            return response()->json([
                'success' => true,
                'snap_token' => $paymentData['snap_token'],
                'order_id' => $paymentData['order_id']
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $payment = Payment::with('tagihan')->where('order_id', $orderId)->first();
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ]);
            }

            // Sinkronisasi status dengan tagihan
            $tagihanStatus = $this->getTagihanStatusFromPayment($payment->status);

            return response()->json([
                'success' => true,
                'payment_status' => $payment->status,
                'tagihan_status' => $tagihanStatus,
                'message' => $this->getStatusMessage($payment->status)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status'
            ]);
        }
    }

    /**
     * Update status tagihan berdasarkan status payment
     */
    private function updateTagihanStatus($tagihan, $paymentStatus)
    {
        try {
            $statusTagihanId = $this->mapPaymentStatusToTagihan($paymentStatus);

            if ($statusTagihanId) {
                // Update status tagihan
                $tagihan->update([
                    'status_tagihan_id' => $statusTagihanId,
                    'tanggal_bayar' => $paymentStatus === 'success' ? now() : null
                ]);

                // Create the next month's tagihan if payment is successful
                if ($paymentStatus === 'success') {
                    $this->createNextMonthTagihan($tagihan);
                }

                Log::info("Status tagihan {$tagihan->id_tagihan} diupdate menjadi {$statusTagihanId} berdasarkan payment status: {$paymentStatus}");
            }
        } catch (\Exception $e) {
            Log::error("Error updating tagihan status: " . $e->getMessage());
        }
    }

    /**
     * Mapping payment status ke status tagihan (UPDATED for 2 status system)
     */
    private function mapPaymentStatusToTagihan($paymentStatus)
    {
        $mapping = [
            // Status Lunas (3)
            'success' => 3,
            'completed' => 3,
            'settlement' => 3,
            'capture' => 3,
            
            // Status Belum Dibayar (1) - semua status gagal/pending
            'pending' => 1,
            'challenge' => 1,
            'processing' => 1,
            'deny' => 1,
            'cancel' => 1,
            'expire' => 1,
            'failure' => 1,
            'error' => 1,
            'unfinish' => 1,
        ];

        return $mapping[$paymentStatus] ?? null;
    }

    /**
     * Get tagihan status berdasarkan payment status (UPDATED)
     */
    private function getTagihanStatusFromPayment($paymentStatus)
    {
        $statusId = $this->mapPaymentStatusToTagihan($paymentStatus);
        
        $statusNames = [
            1 => 'Belum Dibayar',
            3 => 'Lunas'
        ];

        return $statusNames[$statusId] ?? 'Unknown';
    }

    /**
     * Get status message
     */
    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Pembayaran sedang diproses',
            'success' => 'Pembayaran berhasil',
            'settlement' => 'Pembayaran berhasil',
            'capture' => 'Pembayaran berhasil',
            'challenge' => 'Pembayaran sedang diverifikasi',
            'denied' => 'Pembayaran ditolak',
            'expire' => 'Pembayaran kedaluwarsa',
            'cancel' => 'Pembayaran dibatalkan',
            'failure' => 'Pembayaran gagal',
        ];

        return $messages[$status] ?? 'Status tidak diketahui';
    }

    /**
     * Manual update status tagihan dari admin (UPDATED)
     */
    public function updateTagihanStatusManual(Request $request, $tagihanId)
    {
        try {
            $tagihan = Tagihan::findOrFail($tagihanId);
            $newStatusId = $request->input('status_tagihan_id');
            
            // Validasi hanya status 1 atau 3 yang diizinkan
            if (!in_array($newStatusId, [1, 3])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid. Hanya status 1 (Belum Dibayar) atau 3 (Lunas) yang diizinkan.'
                ]);
            }

            // Update status tagihan
            $tagihan->update([
                'status_tagihan_id' => $newStatusId,
                'tanggal_bayar' => $newStatusId == 3 ? now() : null
            ]);

            // Update payment status jika ada
            $payment = Payment::where('tagihan_id', $tagihan->id_tagihan)->first();
            if ($payment) {
                $paymentStatus = $this->mapTagihanStatusToPayment($newStatusId);
                $payment->update(['status' => $paymentStatus]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status'
            ]);
        }
    }

    /**
     * Mapping status tagihan ke payment status (UPDATED)
     */
    private function mapTagihanStatusToPayment($statusTagihanId)
    {
        $mapping = [
            1 => 'pending',      // Belum Dibayar
            3 => 'success',      // Lunas
        ];

        return $mapping[$statusTagihanId] ?? 'pending';
    }

    /**
     * Update payment status (SIMPLIFIED)
     */
    /**
 * Update payment status (FIXED to accept Request)
 */
public function updatePaymentStatus(Request $request, $orderId)
{
    try {
        // Validate request
        $request->validate([
            'status' => 'required|string|in:pending,success,settlement,capture,challenge,deny,cancel,expire,failure,error'
        ]);

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $newStatus = $request->input('status');
        
        // Update payment status
        $payment->update(['status' => $newStatus]);

        if ($payment->tagihan) {
            // Update tagihan status based on payment status
            $this->updateTagihanStatus($payment->tagihan, $newStatus);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully',
            'payment_status' => $newStatus
        ]);

    } catch (\Exception $e) {
        Log::error('Update Payment Status Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update payment status'
        ], 500);
    }
}

    /**
     * Create next month tagihan (placeholder method)
     */
    private function createNextMonthTagihan($tagihan)
    {
        // Implementation for creating next month's tagihan
        // This method should be implemented based on your business logic
        Log::info("Creating next month tagihan for tagihan_id: {$tagihan->id_tagihan}");
    }
}
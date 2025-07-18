<?php

namespace App\Observers;

use App\Models\Tagihan;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class TagihanObserver
{
    /**
     * Handle the Tagihan "updated" event.
     */
    public function updated(Tagihan $tagihan)
    {
        // Cek apakah status_tagihan_id yang berubah
        if ($tagihan->isDirty('status_tagihan_id')) {
            $this->syncPaymentStatus($tagihan);
        }
    }

    /**
     * Sinkronisasi status payment dengan status tagihan
     */
    private function syncPaymentStatus(Tagihan $tagihan)
    {
        try {
            // Cari payment yang terkait dengan tagihan ini
            $payment = Payment::where('tagihan_id', $tagihan->id_tagihan)->first();
            
            if ($payment) {
                // Pemetaan status tagihan ke status payment
                $paymentStatus = $this->mapTagihanStatusToPayment($tagihan->status_tagihan_id);
                
                // Update status payment
                $payment->update([
                    'status' => $paymentStatus,
                    'updated_at' => now()
                ]);

                Log::info("Payment status diupdate menjadi {$paymentStatus} untuk tagihan ID: {$tagihan->id_tagihan}");
            }
        } catch (\Exception $e) {
            Log::error("Error sinkronisasi payment status untuk tagihan {$tagihan->id_tagihan}: " . $e->getMessage());
        }
    }

    /**
     * Mapping status tagihan ke payment status
     */
    private function mapTagihanStatusToPayment($statusTagihanId)
    {
        $mapping = [
            1 => 'pending',      // Belum Dibayar
            2 => 'pending',      // Sedang Diproses  
            3 => 'success',      // Lunas
        ];

        return $mapping[$statusTagihanId] ?? 'pending';
    }
}

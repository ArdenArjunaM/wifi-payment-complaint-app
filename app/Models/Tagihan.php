<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
        'users_id_user',
        'paket_wifi_id_paket_wifi',
        'jumlah_tagihan',
        'jatuh_tempo',
        'status_tagihan_id',
        'bulan_tagihan',
        'no_invoice',
    ];

    protected $casts = [
        'jumlah_tagihan' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    public $timestamps = true;

    // Relasi dengan model lain
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_user', 'id_user');
    }

    public function paketWifi()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_wifi_id_paket_wifi', 'id_paket_wifi');
    }

    public function statusTagihan()
    {
        return $this->belongsTo(StatusTagihan::class, 'status_tagihan_id', 'id_status_tagihan');
    }

    // Relasi dengan pembayaran
    public function payments()
    {
        return $this->hasMany(Payment::class, 'tagihan_id', 'id_tagihan');
    }

    public function pembayaranTerakhir()
    {
        return $this->hasOne(Payment::class, 'tagihan_id', 'id_tagihan')->latest();
    }

    // Getter untuk no_invoice
    public function getNoInvoiceAttribute()
    {
        return $this->attributes['no_invoice'] ?? 'INV-' . str_pad($this->id_tagihan, 6, '0', STR_PAD_LEFT);
    }

    // Getter untuk order_id Midtrans
    public function getMidtransOrderIdAttribute()
    {
        return 'ORDER-' . $this->id_tagihan . '-' . date('Ymd');
    }

    // Method untuk mengecek apakah tagihan sudah dibayar/lunas
    public function isPaid()
    {
        return $this->status_tagihan_id == 2; // Asumsi status_tagihan_id = 2 adalah "Lunas"
    }

    // Method untuk mengecek apakah tagihan sudah lunas (alias untuk isPaid)
    public function isLunas()
    {
        return $this->isPaid();
    }

    // Method untuk mengecek apakah tagihan sudah jatuh tempo
    public function isJatuhTempo()
    {
        return $this->jatuh_tempo < now();
    }

    // Method untuk mendapatkan status pembayaran terakhir
    public function getStatusPembayaranTerakhir()
    {
        $pembayaran = $this->pembayaranTerakhir;
        return $pembayaran ? $pembayaran->status_pembayaran : 'Belum Ada Pembayaran';
    }

    // Scope untuk tagihan yang belum lunas
    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status_tagihan_id', [1]);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_tagihan_id', 3);
    }

    


    public function tagihan()
    {
        return $this->hasOne(Tagihan::class, 'id_pengajuan');
    }

    // Scope untuk tagihan yang sudah jatuh tempo
    public function scopeJatuhTempo($query)
    {
        return $query->where('jatuh_tempo', '<', now());
    }

    // Method untuk membuat order ID yang unik untuk Midtrans
    public function generateMidtransOrderId()
    {
        return 'ORDER-' . $this->id_tagihan . '-' . time();
    }
}
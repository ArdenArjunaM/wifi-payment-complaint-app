<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';
    protected $primaryKey = 'id'; // Tambahkan ini
    public $timestamps = true; // Sesuai dengan struktur tabel

    protected $fillable = [
        'users_id_user',
        'paket_wifi_id_paket_wifi', 
        'status_pengajuan_id'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_user');
    }

    // Relasi dengan PaketWifi
        public function paketWifi()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_wifi_id_paket_wifi', 'id_paket_wifi');
    }

    // Relasi dengan StatusPengajuan
    public function statusPengajuan()
    {
        return $this->belongsTo(StatusPengajuan::class, 'status_pengajuan_id', 'id_status_pengajuan');
    }

    // Relasi dengan Tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_pengajuan', 'id');
    }

    // Relasi ke Tagihan dengan status selesai (status_pengajuan_id = 5)
    public function tagihanSelesai()
    {
        return $this->tagihan()->where('status_pengajuan_id', 5);
    }

    

    // Accessor untuk mendapatkan nama dari pengajuan atau user (fallback ke user)
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->nama : 'N/A';
    }

    // Accessor untuk mendapatkan alamat dari user
    public function getAlamatAttribute()
    {
        return $this->user ? $this->user->alamat : 'N/A';
    }

    // Accessor untuk mendapatkan nomor HP dari user
    public function getNoHpAttribute()
    {
        return $this->user ? $this->user->no_hp : 'N/A';
    }

    // Fungsi untuk mendapatkan nama status pengajuan
    public function getStatusPengajuanName()
    {
        return $this->statusPengajuan ? $this->statusPengajuan->status : 'Unknown';
    }

    
}

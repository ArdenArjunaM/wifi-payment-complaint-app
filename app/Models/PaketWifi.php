<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWifi extends Model
{
    use HasFactory;

    // Secara eksplisit definisikan nama tabel
    protected $table = 'paket_wifi';

    // Jika menggunakan primary key selain 'id', tentukan nama primary key
    protected $primaryKey = 'id_paket_wifi';  // Ganti dengan kolom primary key yang benar

    protected $fillable = [
        'nama_paket',
        'kecepatan',
        'harga_bulanan',
        'deskripsi_paket',
        'gambar',
    ];

    /**
     * Relasi dengan model Pengajuan
     * Setiap paket wifi dapat memiliki banyak pengajuan
     */
    public function pengajuan()
    {
        // Pastikan kolom foreign key dan local key disesuaikan
        return $this->hasMany(Pengajuan::class, 'paket_wifi_id_paket_wifi', 'id_paket_wifi');
    }

    /**
     * Relasi dengan model User
     * Satu paket wifi bisa dimiliki oleh banyak pengguna (melalui pengajuan)
     */
    public function user()
    {
        // Pastikan kolom pivot dan relasi disesuaikan dengan benar
        return $this->belongsToMany(User::class, 'pengajuan', 'paket_wifi_id_paket_wifi', 'users_id_user');
    }

    // Relasi sebaliknya
    public function users()
    {
        return $this->hasMany(User::class, 'paket_wifi_id_paket_wifi'); // Misalnya 'paket_id' di tabel users
    }
}

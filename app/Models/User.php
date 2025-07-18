<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'nama',
        'alamat',
        'no_hp',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $primaryKey = 'id_user';
    protected $keyType = 'int';
    public $incrementing = true;

    // ... relasi lainnya tetap sama


    /**
     * Tentukan relasi model lainnya
     */

    // Relasi User dengan Pengajuan
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'users_id_user', 'id_user');
    }

    // Relasi User dengan Tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'users_id_user', 'id_user');
    }

    // Relasi User dengan PaketWifi (jika dibutuhkan)
    // Relasi antara User dan PaketWifi (satu pelanggan hanya memilih satu paket)
    public function paketWifi()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_wifi_id', 'id');
    }

    // Relasi User dengan Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Fungsi untuk mengambil nama pengguna berdasarkan ID
     */
    public static function getUserNameById($userId)
    {
        $user = self::find($userId); // Lebih efisien menggunakan find()
        return $user ? $user->nama : null; // Mengembalikan null jika user tidak ditemukan
    }

    /**
     * Fungsi untuk mendapatkan nama role yang lebih ramah pengguna
     */
    public function getRoleName()
    {
        // Menggunakan relasi role untuk mendapatkan nama role
        return $this->role ? $this->role->role : 'User'; // Mengambil role atau 'User' jika tidak ada
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTagihan extends Model
{
    protected $table = 'status_tagihan'; // Tentukan nama tabel jika tidak mengikuti konvensi

    // Relasi dengan tabel tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'status_tagihan_id');
    }

    // Relasi dengan tabel payment
    public function payments() // Menggunakan plural karena satu status tagihan bisa memiliki banyak payment
    {
        return $this->hasMany(Payment::class, 'tagihan_id'); // Menggunakan 'tagihan_id' untuk relasi
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai dengan database
    protected $table = 'keluhan';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'nama_keluhan',  // Sesuaikan dengan kolom yang ada di tabel keluhan
    ];

    // Relasi dengan tabel pengaduan
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'keluhan_id_keluhan');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datapelanggan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model
    protected $table = 'datapelanggan';

    // Tentukan kolom mana saja yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama',
        'email',
        'alamat',
        'no_telepon',
    ];

    // Jika menggunakan timestamps
    public $timestamps = true;

    /**
     * Mengurutkan data berdasarkan 'created_at' secara descending (terbaru di atas).
     */
    protected static function booted()
    {
        parent::boot();

        static::addGlobalScope('orderByLatest', function ($builder) {
            $builder->orderBy('created_at', 'desc'); // Atau bisa menggunakan 'id' untuk urutan berdasarkan ID
        });
    }
}

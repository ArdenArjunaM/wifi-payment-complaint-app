<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPengajuan extends Model
{
    // Tabel yang digunakan oleh model ini
    protected $table = 'status_pengajuan';

    // Primary Key
    protected $primaryKey = 'id_status_pengajuan';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'status',  // misalnya 'selesai', 'disetujui', dll.
    ];

    public $timestamps = false;

    // Relasi ke Pengajuan
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'status_pengajuan_id', 'id_status_pengajuan');
    }
}


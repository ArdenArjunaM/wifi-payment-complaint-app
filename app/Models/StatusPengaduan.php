<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPengaduan extends Model
{
    // Menentukan nama tabel yang benar
    protected $table = 'status_pengaduan';  // Perbaiki nama tabel ke status_pengaduan

    // Kolom yang dapat diisi massal
    protected $fillable = ['status'];

    protected $primaryKey = 'id_status_pengajuan'; // Ganti dengan nama kolom primary key yang benar

    public function statusPengaduan()
    {
        return $this->belongsTo(StatusPengaduan::class, 'status_id_status', 'id_status_pengajuan');
    }

}

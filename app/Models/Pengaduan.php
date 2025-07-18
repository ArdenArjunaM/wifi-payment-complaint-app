<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengaduan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model (opsional)
    protected $table = 'pengaduan';

    // Tentukan primary key jika berbeda dari 'id'
    protected $primaryKey = 'id';

    // Tentukan kolom yang boleh diisi - disesuaikan dengan controller
    protected $fillable = [
        'users_id_user',
        'keluhan_id_keluhan', 
        'status_id_status',
    ];

    // Kolom yang akan di-cast ke tipe data tertentu (misalnya timestamps)
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Relasi dengan User (Pengaduan dimiliki oleh User)
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id_user', 'id_user');
    }

    // Relasi dengan Keluhan
    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id_keluhan', 'id_keluhan');
    }

    // Relasi dengan Status Pengaduan
    public function statusPengaduan()
    {
        return $this->belongsTo(StatusPengaduan::class, 'status_id_status', 'id_status_pengaduan');
    }

    // Menambahkan accessor untuk created_at
    public function getCreatedAtAttribute($value)
    {
        // Mengonversi waktu ke zona waktu Asia/Jakarta
        return Carbon::parse($value)->timezone('Asia/Jakarta');
    }

   

    public function index()
    {
        $pengaduan = Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        return view('superadmin.dashboard', compact('pengaduan'));
    }

}

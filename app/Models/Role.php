<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan oleh model ini
    protected $table = 'role';  // Gunakan tabel 'role' bukannya 'roles'
    protected $fillable = ['role'];

    // Tentukan nama kolom primary key
    protected $primaryKey = 'id_role';  // Menggunakan id_role sebagai primary key

    /**
     * Relasi dengan model User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');  // Menghubungkan role dengan users
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'read_at',
        'url',
        'pengaduan_id' // Tambahkan jika ada referensi langsung ke pengaduan
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    protected $appends = [
        'created_at_human',
        'icon',
        'color'
    ];

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Relasi dengan Pengaduan (jika ada foreign key langsung)
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'id');
    }

    /**
     * Get pengaduan dengan detail lengkap beserta relasinya
     */
    public function getPengaduanAttribute()
    {
        // Prioritas 1: Jika ada relasi langsung melalui pengaduan_id
        if ($this->pengaduan_id) {
            return $this->pengaduan()
                ->with(['user', 'keluhan', 'statusPengaduan'])
                ->first();
        }

        // Prioritas 2: Jika data pengaduan_id disimpan di kolom data (JSON)
        if (isset($this->data['pengaduan_id'])) {
            return \App\Models\Pengaduan::with(['user', 'keluhan', 'statusPengaduan'])
                ->find($this->data['pengaduan_id']);
        }

        // Prioritas 3: Jika ada data pengaduan langsung di dalam data (sebagai object)
        if (isset($this->data['pengaduan']) && is_array($this->data['pengaduan'])) {
            return (object) $this->data['pengaduan'];
        }

        return null;
    }

    /**
     * Get pengaduan detail dengan informasi lengkap
     */
    public function getPengaduanDetailAttribute()
    {
        $pengaduan = $this->pengaduan;
        
        if (!$pengaduan) {
            return null;
        }

        // Jika pengaduan adalah object dari database dengan relasi
        if ($pengaduan instanceof \App\Models\Pengaduan) {
            return [
                'id' => $pengaduan->id,
                'created_at' => $pengaduan->created_at,
                'updated_at' => $pengaduan->updated_at,
                'user' => [
                    'id' => $pengaduan->user->id_user ?? null,
                    'name' => $pengaduan->user->name ?? 'Unknown',
                    'email' => $pengaduan->user->email ?? null,
                ],
                'keluhan' => [
                    'id' => $pengaduan->keluhan->id_keluhan ?? null,
                    'judul' => $pengaduan->keluhan->judul ?? 'Unknown',
                    'deskripsi' => $pengaduan->keluhan->deskripsi ?? null,
                ],
                'status' => [
                    'id' => $pengaduan->statusPengaduan->id_status_pengaduan ?? null,
                    'nama_status' => $pengaduan->statusPengaduan->nama_status ?? 'Unknown',
                    'deskripsi' => $pengaduan->statusPengaduan->deskripsi ?? null,
                ]
            ];
        }

        // Jika pengaduan adalah object dari JSON data
        return (array) $pengaduan;
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope untuk notifikasi berdasarkan pengaduan
     */
    public function scopeByPengaduan($query, $pengaduanId)
    {
        return $query->where(function($q) use ($pengaduanId) {
            $q->where('pengaduan_id', $pengaduanId)
              ->orWhereJsonContains('data->pengaduan_id', $pengaduanId);
        });
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get human readable created date
     */
    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get icon based on notification type
     */
    public function getIconAttribute()
    {
        switch ($this->type) {
            case 'pengaduan_baru':
            case 'new_submission':
                return 'fas fa-file-alt';
            case 'pengaduan_diproses':
            case 'status_change':
                return 'fas fa-sync-alt';
            case 'pengaduan_selesai':
                return 'fas fa-check-circle';
            case 'payment_reminder':
                return 'fas fa-credit-card';
            case 'installation':
                return 'fas fa-tools';
            case 'info':
                return 'fas fa-info-circle';
            case 'warning':
                return 'fas fa-exclamation-triangle';
            case 'success':
                return 'fas fa-check';
            case 'danger':
                return 'fas fa-exclamation-circle';
            default:
                return 'fas fa-bell';
        }
    }

    /**
     * Get color based on notification type
     */
    public function getColorAttribute()
    {
        switch ($this->type) {
            case 'pengaduan_baru':
            case 'new_submission':
                return 'danger';
            case 'pengaduan_diproses':
                return 'warning';
            case 'pengaduan_selesai':
            case 'status_change':
                return 'success';
            case 'payment_reminder':
                return 'warning';
            case 'installation':
                return 'info';
            case 'info':
                return 'info';
            case 'warning':
                return 'warning';
            case 'success':
                return 'success';
            case 'danger':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Get notification data safely
     */
    public function getDataAttribute($value)
    {
        $data = json_decode($value, true) ?? [];
        
        // Ensure data is always an array
        if (!is_array($data)) {
            return [];
        }

        return $data;
    }

    /**
     * Set notification data
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get safe title
     */
    public function getTitleAttribute($value)
    {
        return $value ?? 'Notifikasi';
    }

    /**
     * Get safe message
     */
    public function getMessageAttribute($value)
    {
        return $value ?? 'Tidak ada pesan';
    }

    /**
     * Helper method untuk membuat notifikasi pengaduan
     */
    public static function createPengaduanNotification($userId, $pengaduanId, $type, $title, $message, $additionalData = [])
    {
        $data = array_merge([
            'pengaduan_id' => $pengaduanId,
            'timestamp' => now()->toISOString()
        ], $additionalData);

        return self::create([
            'user_id' => $userId,
            'pengaduan_id' => $pengaduanId, // Jika menggunakan foreign key langsung
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false
        ]);
    }
}
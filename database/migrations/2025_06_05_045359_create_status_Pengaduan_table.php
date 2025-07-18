<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStatusPengaduanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Membuat tabel status_pengaduan
        Schema::create('status_pengaduan', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('status', 50)->unique();  // Kolom status untuk menyimpan nama status pengaduan
            $table->timestamps();  // Kolom created_at dan updated_at
        });

        // Menambahkan status pengaduan default
        DB::table('status_pengaduan')->insert([
            ['status' => 'Diproses'],
            ['status' => 'Selesai'],
            ['status' => 'Ditolak'],
            ['status' => 'Menunggu'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel status_pengaduan jika migrasi dibatalkan
        Schema::dropIfExists('status_pengaduan');
    }
};

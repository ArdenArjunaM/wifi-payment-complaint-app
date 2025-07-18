<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('users_id_user')->constrained('users')->onDelete('cascade');  // Relasi dengan tabel users
            $table->foreignId('paket_wifi_id_paket_wifi')->constrained('paket_wifi')->onDelete('cascade');  // Relasi dengan tabel paket_wifi
            $table->foreignId('status_pengajuan_id')->constrained('status_pengajuan')->onDelete('cascade');  // Relasi dengan tabel status_pengajuan
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};

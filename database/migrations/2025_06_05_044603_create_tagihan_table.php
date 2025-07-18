<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->bigIncrements('id_tagihan');  // BigInt sebagai Primary Key
            $table->decimal('jumlah_tagihan', 10, 2); // Jumlah tagihan
            $table->date('jatuh_tempo'); // Tanggal jatuh tempo
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi dengan tabel users
            $table->unsignedBigInteger('users_id_user'); // Foreign key ke users
            $table->foreign('users_id_user')->references('id_user')->on('users')->onDelete('cascade');

            // Relasi dengan tabel paket_wifi
            $table->unsignedBigInteger('paket_wifi_id_paket_wifi'); // Foreign key ke paket_wifi
            $table->foreign('paket_wifi_id_paket_wifi')->references('id_paket_wifi')->on('paket_wifi')->onDelete('cascade');

            // Relasi dengan tabel status_tagihan
            $table->unsignedBigInteger('status_tagihan_id'); // Foreign key ke status_tagihan
            $table->foreign('status_tagihan_id')->references('id_status_tagihan')->on('status_tagihan')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
}
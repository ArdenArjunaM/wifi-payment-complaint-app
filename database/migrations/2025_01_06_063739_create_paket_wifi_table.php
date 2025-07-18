<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketWifiTable extends Migration
{
    public function up()
    {
        Schema::create('paket_wifi', function (Blueprint $table) {
            $table->bigIncrements('id_paket_wifi');  // Ganti kolom ID menjadi 'id_paket_wifi' sebagai primary key
            $table->string('nama_paket', 50);  // Nama paket Wi-Fi (menyesuaikan panjang varchar)
            $table->string('kecepatan', 20);  // Kecepatan paket Wi-Fi (menyesuaikan panjang varchar)
            $table->decimal('harga_bulanan', 10, 2);  // Harga bulanan paket Wi-Fi
            $table->text('deskripsi_paket');  // Deskripsi paket Wi-Fi
            $table->string('gambar')->nullable();  // Gambar (optional) disimpan sebagai path
            $table->timestamps();  // Timestamps untuk created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('paket_wifi');  // Menghapus tabel jika rollback migration
    }
}


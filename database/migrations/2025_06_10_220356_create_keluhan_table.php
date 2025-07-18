<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateKeluhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keluhan', function (Blueprint $table) {
            $table->id('id_keluhan');  // Primary key untuk ID keluhan
            $table->string('nama_keluhan');  // Kolom untuk nama atau deskripsi keluhan
            $table->timestamps();  // Kolom created_at dan updated_at
        });

        // Menambahkan data default untuk keluhan
        DB::table('keluhan')->insert([
            ['nama_keluhan' => 'Indikator Alat Merah'],
            ['nama_keluhan' => 'Router Mati'],
            ['nama_keluhan' => 'Jaringan Lambat'],
            ['nama_keluhan' => 'Koneksi Terputus'],
            ['nama_keluhan' => 'Gangguan Layanan'],
            ['nama_keluhan' => 'Adaptor Rusak'],  // Menambahkan "Adaptor Rusak"
            ['nama_keluhan' => 'Layanan Tidak Tersedia'], // Contoh tambahan lainnya
            ['nama_keluhan' => 'Jaringan Tidak Stabil'], // Contoh tambahan lainnya
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keluhan');
    }
}

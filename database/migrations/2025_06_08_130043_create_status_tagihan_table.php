<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTagihanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_tagihan', function (Blueprint $table) {
            $table->bigIncrements('id_status_tagihan');  // Primary Key menggunakan BigInt
            $table->string('status', 20); // Status tagihan (misalnya: Lunas, Belum Dibayar)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_tagihan');
    }
}

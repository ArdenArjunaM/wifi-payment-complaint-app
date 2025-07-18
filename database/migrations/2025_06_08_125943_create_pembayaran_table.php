<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');  // Primary Key
            $table->decimal('jumlah_pembayaran', 10, 2); // Jumlah pembayaran
            $table->date('tanggal_pembayaran'); // Tanggal pembayaran
            $table->string('metode_pembayaran'); // Metode pembayaran (misalnya Midtrans)
            $table->string('status_pembayaran'); // Status pembayaran (misalnya berhasil, gagal)
            $table->string('midtrans_transaction_id')->nullable(); // ID transaksi Midtrans
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi dengan tabel tagihan
            $table->unsignedBigInteger('id_tagihan'); // Foreign key ke tagihan
            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihan')->onDelete('cascade');

            // Relasi dengan tabel status_tagihan (menggunakan BigInt untuk foreign key)
            $table->unsignedBigInteger('status_tagihan_id'); // Foreign key ke status_tagihan
            $table->foreign('status_tagihan_id')->references('id_status_tagihan')->on('status_tagihan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel users.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');  // Kolom id_user, primary key dengan auto increment
            $table->string('username', 10);  // Kolom username dengan panjang maksimal 10 karakter
            $table->string('password');  // Password pengguna
            $table->string('nama', 50);  // Nama pengguna dengan panjang maksimal 50 karakter
            $table->string('email', 50)->unique();  // Email pengguna dengan panjang maksimal 50 karakter (unik)
            $table->string('no_hp', 15);  // Nomor handphone pengguna dengan panjang maksimal 15 karakter
            $table->string('status', 20)->default('active');  // Status akun pengguna dengan panjang maksimal 20 karakter dan default 'active'
            $table->foreignId('role_id')->constrained('role')->default(2);  // Kolom role_id yang merujuk ke tabel role dengan default 'user' (role_id 2)
            $table->string('alamat', 100);  // Alamat pengguna dengan panjang maksimal 100 karakter
            $table->timestamp('email_verified_at')->nullable();  // Verifikasi email (nullable)
            $table->rememberToken();  // Token untuk "remember me"
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Membalikkan migrasi dan menghapus tabel users.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');  // Menghapus tabel jika rollback migrasi
    }
}

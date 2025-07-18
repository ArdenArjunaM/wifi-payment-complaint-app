<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Import model User
use Carbon\Carbon;  // Untuk bekerja dengan tanggal
use Faker\Factory as Faker;  // Import Faker untuk menghasilkan data acak

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();  // Membuat instance Faker

        // Menambahkan 10 data pengguna ke tabel 'users'
        foreach (range(1, 10) as $index) {
            // Pilih nama acak dari daftar yang ada
            $randomName = $faker->randomElement(['Budi', 'Dani', 'Dono', 'Dini', 'Aldi', 'Siti', 'Rani', 'Wati', 'Rudi', 'Rika']);

            User::create([
                'username' => strtolower($randomName) . $index,  // Menghasilkan username seperti budi1, dani2, dst
                'password' => Hash::make('password' . $index),  // Password yang di-hash
                'nama' => $randomName . ' User ' . $index,  // Nama pengguna, misalnya Budi User 1
                'email' => strtolower($randomName) . $index . '@gmail.com',  // Email pengguna dengan domain @gmail.com
                'no_hp' => '08123456789' . $index,  // Nomor handphone pengguna
                'status' => 'Belum Berlangganan',
                'role_id' => 2,  // Menetapkan role_id 2 untuk 'user'
                'alamat' => 'Alamat ' . $randomName . ' No. ' . $index,  // Alamat pengguna
                'email_verified_at' => Carbon::create(2024, 12, 1),  // Verifikasi email dimulai dari November 2024
                'created_at' => Carbon::create(2024, 12, 1),  // Tanggal pembuatan dimulai 1 Desember 2024
                'updated_at' => Carbon::create(2024, 12, 1),  // Tanggal pembaruan dimulai 1 Desember 2024
            ]);
        }
    }
}

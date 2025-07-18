<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Menambahkan import DB

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('role')->insert([
            ['role' => 'admin'],
            ['role' => 'user'],
            ['role' => 'superadmin'],
            ['role' => 'teknisi'],
        ]);
    }

}

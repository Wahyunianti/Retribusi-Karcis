<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'administrator',
        ]);

        Role::create([
            'role_name' => 'admin',
        ]);

        Role::create([
            'role_name' => 'kepala',
        ]);

        User::create([
            'nama' => 'Wahyuni Anti',
            'username' => 'wahyuni',
            'nip'=> 2131730117,
            'no_telp'=> '081946174344',
            'password' => bcrypt('administrator'),
            'status' => 'PNS',
            'bagian' => 'pengambil karcis',
            'foto' => '',
            'role_id' => 1
        ]);

    }
}

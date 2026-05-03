<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User_UKS;
use \Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User_UKS::create([
            'name' => 'Admin Pembina',
            'email' => 'admin@uks.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User_UKS::create([
            'name' => 'Petugas PMR',
            'email' => 'petugas@uks.com',
            'password' => Hash::make('password'),
            'role' => 'petugas'
        ]);
    }
}

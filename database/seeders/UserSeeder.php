<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Txreey',
            'email' => 'txreey@gmail.com',
            'password' => Hash::make('txreey666'),
            'role' => 'admin',
            'phone' => '081297765432',
            'address' => 'Parung, Subang, Jawa Barat'
        ]);

        User::create([
            'name' => 'Putra',
            'email' => 'putra@gmail.com',
            'password' => Hash::make('putra666'),
            'role' => 'customer',
            'phone' => '081298765432',
            'address' => 'Plamboyan, Subang, Jawa Barat'
        ]);
    }
}

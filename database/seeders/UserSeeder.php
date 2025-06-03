<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Infoma',
            'email' => 'admin@infoma.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        User::create([
            'name' => 'Provider Kost',
            'email' => 'provider@infoma.com',
            'password' => Hash::make('password'),
            'role' => 'provider',
            'phone' => '081234567891',
        ]);

        User::create([
            'name' => 'Mahasiswa 1',
            'email' => 'user1@infoma.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567892',
            'university' => 'Telkom University',
            'major' => 'Teknik Informatika',
        ]);

        User::create([
            'name' => 'Mahasiswa 2',
            'email' => 'user2@infoma.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567893',
            'university' => 'Telkom University',
            'major' => 'Sistem Informasi',
        ]);
    }
} 
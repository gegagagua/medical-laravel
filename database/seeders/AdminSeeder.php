<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin user.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'gegagagua@gmail.com'],
            [
                'first_name' => 'Gega',
                'last_name' => 'Gagua',
                'password' => Hash::make('NiNuca199@'),
                'role' => 'ADMIN',
                'email_verified_at' => now(),
            ]
        );
    }
}

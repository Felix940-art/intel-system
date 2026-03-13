<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'tico.dorado.2022@gmail.com'],
            [
                'name' => 'Command Control',
                'password' => Hash::make('8484tico'),
            ]
        );
    }
}

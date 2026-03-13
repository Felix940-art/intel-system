<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'tico.dorado.2022@gmail.com'],
            [
                'name' => 'Command Control',
                'password' => Hash::make('8484tico'),
                'role' => 'Admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@agency.gov'],
            [
                'name' => 'Field Operator',
                'password' => Hash::make('operator123'),
                'role' => 'Operator',
            ]
        );
    }
}

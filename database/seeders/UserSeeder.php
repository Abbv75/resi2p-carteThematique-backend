<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('id', 'R01')->first();
        $standardRole = Role::where('id', 'R02')->first();

        User::updateOrCreate(
            ['email' => 'bkalilou91@gmail.com'],
            [
                'nom' => 'Berthé',
                'prenom' => 'Kalilou',
                'password' => Hash::make('Berthe@FIER2_2026!'),
                'id_role' => $adminRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'nom' => 'User',
                'prenom' => 'Assistant',
                'password' => Hash::make('password'),
                'id_role' => $standardRole->id,
            ]
        );
    }
}


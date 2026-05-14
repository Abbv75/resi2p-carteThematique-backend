<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['id' => 'R01'],
            [
                'nom' => 'Administrateur',
                'description' => 'Administrateur du système',
            ]
        );

        Role::updateOrCreate(
            ['id' => 'R02'],
            [
                'nom' => 'assistant suivi évaluation',
                'description' => 'Assistant en suivi et évaluation',
            ]
        );
    }
}

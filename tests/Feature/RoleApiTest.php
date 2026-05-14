<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all roles.
     */
    public function test_can_get_all_roles(): void
    {
        // Seed some roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Liste des rôles récupérée avec succès',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'nom',
                        'description',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        // Based on our RoleSeeder update earlier, we expect 2 roles (R01, R02)
        $this->assertCount(2, $response->json('data'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all users.
     */
    public function test_can_get_all_users(): void
    {
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Liste des utilisateurs récupérée avec succès',
            ]);
        
        $this->assertCount(5, $response->json('data'));
    }

    /**
     * Test getting a specific user profile.
     */
    public function test_can_get_user_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profil utilisateur',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nom',
                    'prenom',
                    'email',
                    'id_role',
                    'telephone',
                    'site_web',
                    'role'
                ]
            ]);
    }

    /**
     * Test 404 for non-existent user.
     */
    public function test_returns_404_for_non_existent_user(): void
    {
        $response = $this->getJson('/api/users/99999999-9999-9999-9999-999999999999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Utilisateur non trouvé ou erreur serveur.'
            ]);
    }

    /**
     * Test getting a user's maps.
     */
    public function test_can_get_user_maps(): void
    {
        $user = User::factory()->create();
        \App\Models\Map::factory()->count(3)->create(['id_user' => $user->id]);
        
        // Create another map for different user to ensure filtering works
        \App\Models\Map::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}/maps");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cartes de l\'utilisateur récupérées avec succès',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'id_thematique',
                        'thematique'
                    ]
                ]
            ]);
        
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test updating a user.
     */
    public function test_can_update_user(): void
    {
        $user = User::factory()->create([
            'nom' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'nom' => 'New Name',
            'email' => 'new@example.com',
            'telephone' => '87654321',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => [
                    'nom' => 'New Name',
                    'email' => 'new@example.com',
                    'telephone' => '87654321',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nom' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    /**
     * Test updating a user's role.
     */
    public function test_can_update_user_role(): void
    {
        $this->seed();
        $user = User::factory()->create(['id_role' => 'R01']);

        $response = $this->putJson("/api/users/{$user->id}", [
            'id_role' => 'R02',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id_role' => 'R02',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'id_role' => 'R02',
        ]);
    }

    /**
     * Test update fails with existing email of another user.
     */
    public function test_update_fails_with_existing_email(): void
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->putJson("/api/users/{$user1->id}", [
            'email' => 'user2@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test deleting a user.
     */
    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}

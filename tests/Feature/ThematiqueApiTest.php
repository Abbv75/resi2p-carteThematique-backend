<?php

namespace Tests\Feature;

use App\Models\Thematique;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThematiqueApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index route.
     */
    public function test_can_list_all_thematiques(): void
    {
        Thematique::factory()->count(2)->create();

        $response = $this->getJson('/api/thematiques');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Liste des thématiques récupérée avec succès',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description']
                ]
            ]);
        
        $this->assertCount(2, $response->json('data'));
    }

    /**
     * Test the store route.
     */
    public function test_can_create_a_thematique(): void
    {
        $data = [
            'id' => 'test-thematique',
            'title' => 'Test Title',
            'description' => 'Test Description',
        ];

        $response = $this->postJson('/api/thematiques', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Thématique créée avec succès',
                'data' => [
                    'id' => 'test-thematique',
                    'title' => 'Test Title',
                    'description' => 'Test Description',
                ]
            ]);
        
        $this->assertDatabaseHas('thematiques', ['id' => 'test-thematique']);
    }

    /**
     * Test store validation.
     */
    public function test_store_validation_fails_for_missing_fields(): void
    {
        $response = $this->postJson('/api/thematiques', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id', 'title', 'description']);
    }

    /**
     * Test the update route.
     */
    public function test_can_update_a_thematique(): void
    {
        $thematique = Thematique::create([
            'id' => 'id-to-update',
            'title' => 'Old Title',
            'description' => 'Old Description',
        ]);

        $updateData = [
            'title' => 'Updated Title',
        ];

        $response = $this->putJson("/api/thematiques/{$thematique->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thématique mise à jour avec succès',
                'data' => [
                    'title' => 'Updated Title',
                    'description' => 'Old Description',
                ]
            ]);
        
        $this->assertDatabaseHas('thematiques', [
            'id' => 'id-to-update',
            'title' => 'Updated Title'
        ]);
    }

    /**
     * Test deleting a thematique and its cascading effect.
     */
    public function test_can_delete_thematique(): void
    {
        $thematique = Thematique::factory()->create(['id' => 'to-delete']);
        $map = \App\Models\Map::factory()->create(['id_thematique' => $thematique->id]);

        $response = $this->deleteJson("/api/thematiques/{$thematique->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thématique supprimée avec succès'
            ]);

        $this->assertDatabaseMissing('thematiques', ['id' => 'to-delete']);
        $this->assertDatabaseMissing('maps', ['id' => $map->id]);
    }

    /**
     * Test update with non-existent ID.
     */
    public function test_update_returns_404_for_non_existent_thematique(): void
    {
        $response = $this->putJson('/api/thematiques/non-existent-id', [
            'title' => 'Title'
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Thématique non trouvée.'
            ]);
    }
}

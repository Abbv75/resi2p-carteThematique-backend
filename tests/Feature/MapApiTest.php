<?php

namespace Tests\Feature;

use App\Models\Map;
use App\Models\Thematique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MapApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index route for maps.
     */
    public function test_can_list_all_maps(): void
    {
        Map::factory()->count(3)->create();

        $response = $this->getJson('/api/maps');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Liste des cartes récupérée avec succès',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'id_thematique',
                        'id_user',
                        'title',
                        'description',
                        'url',
                        'downloadUrl',
                        'thumbnail',
                        'thematique',
                        'user'
                    ]
                ]
            ]);
        
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test mapping creation with thumbnail upload.
     */
    public function test_can_create_map_with_thumbnail(): void
    {
        $thematique = Thematique::factory()->create();
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('thumb.jpg');

        $data = [
            'id_thematique' => $thematique->id,
            'id_user' => $user->id,
            'title' => 'New Map',
            'description' => 'New Description',
            'url' => 'http://example.com/map',
            'thumbnail' => $image,
        ];

        $response = $this->postJson('/api/maps', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Carte créée avec succès',
            ]);
        
        $thumbnailPath = $response->json('data.thumbnail');
        $this->assertNotNull($thumbnailPath);
        $this->assertFileExists(public_path($thumbnailPath));

        // Cleanup
        if (File::exists(public_path($thumbnailPath))) {
            File::delete(public_path($thumbnailPath));
        }
    }

    /**
     * Test the show route for a specific map.
     */
    public function test_can_get_specific_map_details(): void
    {
        $map = Map::factory()->create();

        $response = $this->getJson("/api/maps/{$map->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Détails de la carte récupérés avec succès',
                'data' => [
                    'id' => $map->id,
                    'title' => $map->title,
                ]
            ]);
    }

    /**
     * Test updating a map with a new thumbnail.
     */
    public function test_can_update_map_with_new_thumbnail(): void
    {
        $map = Map::factory()->create(['thumbnail' => null]);
        $newImage = UploadedFile::fake()->image('new_thumb.png');

        $response = $this->putJson("/api/maps/{$map->id}", [
            'title' => 'Updated Title',
            'thumbnail' => $newImage,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Carte mise à jour avec succès',
                'data' => [
                    'title' => 'Updated Title',
                ]
            ]);

        $thumbnailPath = $response->json('data.thumbnail');
        $this->assertFileExists(public_path($thumbnailPath));

        // Cleanup
        if (File::exists(public_path($thumbnailPath))) {
            File::delete(public_path($thumbnailPath));
        }
    }

    /**
     * Test deleting a map.
     */
    public function test_can_delete_map(): void
    {
        // First create a map with a real physical file to test deletion
        $image = UploadedFile::fake()->image('to_delete.png');
        $response = $this->postJson('/api/maps', [
            'id_thematique' => Thematique::factory()->create()->id,
            'title' => 'Title',
            'description' => 'Desc',
            'url' => 'http://test.com',
            'thumbnail' => $image,
        ]);
        
        $mapId = $response->json('data.id');
        $thumbnailPath = $response->json('data.thumbnail');
        $this->assertFileExists(public_path($thumbnailPath));

        $response = $this->deleteJson("/api/maps/{$mapId}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('maps', ['id' => $mapId]);
        $this->assertFileDoesNotExist(public_path($thumbnailPath));
    }

    /**
     * Test that show route returns 404 for non-existent map.
     */
    public function test_returns_404_for_non_existent_map(): void
    {
        $response = $this->getJson('/api/maps/99999999-9999-9999-9999-999999999999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Carte non trouvée ou erreur serveur.'
            ]);
    }
}

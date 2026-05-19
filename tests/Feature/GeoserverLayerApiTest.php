<?php

namespace Tests\Feature;

use App\Models\GeoserverLayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeoserverLayerApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index route of geoserver-layers.
     */
    public function test_can_list_all_geoserver_layers(): void
    {
        GeoserverLayer::factory()->count(3)->create();

        $response = $this->getJson('/api/geoserver-layers');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Liste des couches GeoServer récupérée avec succès',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'title',
                        'name',
                        'openlayerUrl',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('data'));
    }
}

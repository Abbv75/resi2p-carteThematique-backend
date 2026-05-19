<?php

namespace Tests\Feature;

use App\Models\GeoserverLayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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

    /**
     * Test successful sync from GeoServer WMS GetCapabilities.
     */
    public function test_can_sync_geoserver_layers(): void
    {
        $xmlCapabilities = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<WMS_Capabilities version="1.1.1">
  <Capability>
    <Layer>
      <Layer>
        <Name>resi2p:thematique_point</Name>
        <Title>Thematique point</Title>
      </Layer>
      <Layer>
        <Name>resi2p:thematique_polygon</Name>
        <Title>Thematique polygon</Title>
      </Layer>
    </Layer>
  </Capability>
</WMS_Capabilities>
XML;

        // Fake the Http call to GeoServer wms GetCapabilities
        Http::fake([
            '158.220.120.218:8080/*' => Http::response($xmlCapabilities, 200, ['Content-Type' => 'text/xml'])
        ]);

        $response = $this->getJson('/api/geoserver-layers/sync');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'count' => 2,
            ])
            ->assertJsonStructure([
                'success',
                'count',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'title',
                        'openlayerUrl',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('geoserver_layers', [
            'name' => 'resi2p:thematique_point',
            'type' => 'resi2p',
            'title' => 'Thematique point',
        ]);

        $this->assertDatabaseHas('geoserver_layers', [
            'name' => 'resi2p:thematique_polygon',
            'type' => 'resi2p',
            'title' => 'Thematique polygon',
        ]);
    }

    /**
     * Test failed sync when GeoServer returns error.
     */
    public function test_sync_fails_when_geoserver_fails(): void
    {
        // Fake a failure response from GeoServer
        Http::fake([
            '158.220.120.218:8080/*' => Http::response([], 500)
        ]);

        $response = $this->getJson('/api/geoserver-layers/sync');

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'Impossible de récupérer GetCapabilities',
            ]);
    }

    /**
     * Test sync fails when invalid XML is returned.
     */
    public function test_sync_fails_for_invalid_xml(): void
    {
        // Fake an invalid XML response
        Http::fake([
            '158.220.120.218:8080/*' => Http::response('invalid xml content', 200, ['Content-Type' => 'text/xml'])
        ]);

        $response = $this->getJson('/api/geoserver-layers/sync');

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'XML invalide',
            ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\GeoserverLayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class GeoserverLayerController extends Controller
{
    /**
     * Display a listing of geoserver layers.
     */
    public function index(): JsonResponse
    {
        try {
            $layers = GeoserverLayer::latest()->get();
            return $this->success($layers, 'Liste des couches GeoServer récupérée avec succès');
        } catch (Exception $e) {
            return $this->error('Erreur lors de la récupération des couches GeoServer : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Synchronize layers from GeoServer.
     */
    public function sync(): JsonResponse
    {
        try {
            $url = "http://158.220.120.218:8080/geoserver/rest/layers.json";

            $response = Http::withBasicAuth('admin', 'M8r12p14j3')
                ->get($url);

            if (!$response->successful()) {
                return $this->error('Impossible de récupérer GeoServer', null, 500);
            }

            $layers = $response->json()['layers']['layer'] ?? [];

            $saved = 0;

            foreach ($layers as $layer) {
                $name = $layer['name']; // tiger:poi

                GeoserverLayer::updateOrCreate(
                    ['name' => $name],
                    [
                        'type' => explode(':', $name)[0] ?? 'default',
                        'title' => ucfirst(str_replace('_', ' ', explode(':', $name)[1] ?? $name)),
                        'openlayerUrl' => $this->buildWmsUrl($name)
                    ]
                );

                $saved++;
            }

            return $this->success(['count' => $saved], 'Synchronisation réussie');
        } catch (Exception $e) {
            return $this->error('Erreur lors de la synchronisation : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Build the WMS URL for a layer.
     */
    private function buildWmsUrl(string $layer): string
    {
        return "http://158.220.120.218:8080/geoserver/wms?service=WMS&version=1.1.0&request=GetMap"
            . "&layers={$layer}"
            . "&styles="
            . "&bbox={bbox-epsg-3857}"
            . "&width=256&height=256"
            . "&srs=EPSG:3857"
            . "&format=image/png";
    }
}

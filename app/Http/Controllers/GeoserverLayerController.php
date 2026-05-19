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
     * Synchronize layers from GeoServer WMS GetCapabilities.
     */
    public function syncFromWms(): JsonResponse
    {
        try {
            $url = "http://158.220.120.218:8080/geoserver/wms?service=WMS&request=GetCapabilities";

            $response = Http::timeout(60)->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de récupérer GetCapabilities'
                ], 500);
            }

            $xml = @simplexml_load_string($response->body());

            if (!$xml) {
                return response()->json([
                    'success' => false,
                    'message' => 'XML invalide'
                ], 500);
            }

            $layers = [];
            $count = 0;

            foreach ($xml->Capability->Layer->Layer as $layer) {
                $name = (string) $layer->Name;
                $title = (string) $layer->Title;

                if (!$name) continue;

                $workspace = explode(':', $name)[0] ?? 'default';
                $shortName = explode(':', $name)[1] ?? $name;

                $record = GeoserverLayer::updateOrCreate(
                    ['name' => $name],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => $workspace,
                        'title' => $title ?: ucfirst(str_replace('_', ' ', $shortName)),
                        'openlayerUrl' => $this->buildWmsUrl($name)
                    ]
                );

                $layers[] = $record;
                $count++;
            }

            return response()->json([
                'success' => true,
                'count' => $count,
                'data' => $layers
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la synchronisation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build the WMS URL for a layer.
     */
    private function buildWmsUrl($layer)
    {
        return "http://158.220.120.218:8080/geoserver/wms"
            . "?service=WMS&version=1.1.0&request=GetMap"
            . "&layers={$layer}"
            . "&styles="
            . "&bbox={bbox-epsg-3857}"
            . "&width=256&height=256"
            . "&srs=EPSG:3857"
            . "&format=image/png";
    }
}

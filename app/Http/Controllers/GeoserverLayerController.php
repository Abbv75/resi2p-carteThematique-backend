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
     * Synchronize layers from GeoServer WMS.
     */
    public function syncFromWms(): JsonResponse
    {
        try {
            $geoserverUrl = env('GEOSERVER_URL', 'http://158.220.120.218:8080/geoserver');
            
            // Fetch WMS Capabilities
            try {
                $response = Http::get($geoserverUrl . '/wms', [
                    'service' => 'WMS',
                    'version' => '1.1.1',
                    'request' => 'GetCapabilities',
                ]);

                if (!$response->successful()) {
                    return $this->error('Impossible de récupérer GetCapabilities', null, 500);
                }
            } catch (Exception $e) {
                return $this->error('Impossible de récupérer GetCapabilities', null, 500);
            }

            // Suppress errors and load XML safely
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($response->body());
            if ($xml === false) {
                libxml_clear_errors();
                return $this->error('XML invalide', null, 500);
            }
            libxml_clear_errors();

            // Find all Layer elements namespace-agnostically
            $layers = $xml->xpath('//*[local-name()="Layer"]');
            
            $syncedLayers = [];
            $wmsNames = [];

            foreach ($layers as $layer) {
                // Look for Name child
                $nameNodes = $layer->xpath('*[local-name()="Name"]');
                if (empty($nameNodes)) {
                    continue;
                }

                $name = trim((string) $nameNodes[0]);
                if (empty($name)) {
                    continue;
                }

                // Look for Title child, fallback to Name
                $titleNodes = $layer->xpath('*[local-name()="Title"]');
                $title = !empty($titleNodes) ? trim((string) $titleNodes[0]) : $name;

                // Extract prefix/workspace from name as type
                $parts = explode(':', $name);
                $type = count($parts) > 1 ? $parts[0] : 'default';

                // Try to extract LatLonBoundingBox or BoundingBox from layer metadata
                $bboxStr = '-180,-90,180,90';
                $latLonBbox = $layer->xpath('*[local-name()="LatLonBoundingBox"]');
                if (!empty($latLonBbox)) {
                    $attrs = $latLonBbox[0]->attributes();
                    if (isset($attrs['minx'], $attrs['miny'], $attrs['maxx'], $attrs['maxy'])) {
                        $bboxStr = (string)$attrs['minx'] . ',' . (string)$attrs['miny'] . ',' . (string)$attrs['maxx'] . ',' . (string)$attrs['maxy'];
                    }
                } else {
                    $bbox = $layer->xpath('*[local-name()="BoundingBox"]');
                    if (!empty($bbox)) {
                        $attrs = $bbox[0]->attributes();
                        if (isset($attrs['minx'], $attrs['miny'], $attrs['maxx'], $attrs['maxy'])) {
                            $bboxStr = (string)$attrs['minx'] . ',' . (string)$attrs['miny'] . ',' . (string)$attrs['maxx'] . ',' . (string)$attrs['maxy'];
                        }
                    }
                }

                // Construct OpenLayers preview URL for the layer including BBOX
                $openlayerUrl = $geoserverUrl . '/wms?' . http_build_query([
                    'service' => 'WMS',
                    'version' => '1.1.0',
                    'request' => 'GetMap',
                    'layers' => $name,
                    'bbox' => $bboxStr,
                    'width' => '768',
                    'height' => '692',
                    'srs' => 'EPSG:4326',
                    'format' => 'application/openlayers',
                ]);

                // Create or update local cached layer record
                $geoserverLayer = GeoserverLayer::updateOrCreate(
                    ['name' => $name],
                    [
                        'title' => $title,
                        'type' => $type,
                        'openlayerUrl' => $openlayerUrl,
                    ]
                );

                $syncedLayers[] = $geoserverLayer;
                $wmsNames[] = $name;
            }

            // Optional: clean up local layers no longer present on the WMS server
            GeoserverLayer::whereNotIn('name', $wmsNames)->delete();

            return response()->json([
                'success' => true,
                'count' => count($syncedLayers),
                'data' => $syncedLayers,
            ]);

        } catch (Exception $e) {
            return $this->error('Une erreur est survenue lors de la synchronisation : ' . $e->getMessage(), null, 500);
        }
    }
}

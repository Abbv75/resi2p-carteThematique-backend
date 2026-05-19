<?php

namespace App\Http\Controllers;

use App\Models\GeoserverLayer;
use Illuminate\Http\JsonResponse;
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
}

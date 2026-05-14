<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Map;
use App\Http\Requests\Map\CreateMapRequest;
use App\Http\Requests\Map\UpdateMapRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;

class MapController extends Controller
{
    /**
     * Display a listing of the maps.
     */
    public function index()
    {
        try {
            $maps = Map::with(['thematique', 'user'])->latest()->get();
            return $this->success($maps, 'Liste des cartes récupérée avec succès');
        } catch (Exception $e) {
            return $this->error('Une erreur est survenue lors de la récupération des cartes.', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created map.
     */
    public function store(CreateMapRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $image = $request->file('thumbnail');
                $filename = Str::random(32) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/maps'), $filename);
                $data['thumbnail'] = '/images/maps/' . $filename;
            }

            $map = Map::create($data);
            $map->load(['thematique', 'user']);
            
            return $this->success($map, 'Carte créée avec succès', 201);
        } catch (Exception $e) {
            return $this->error('Erreur lors de la création de la carte : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified map.
     */
    public function update(UpdateMapRequest $request, $id)
    {
        try {
            $map = Map::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if it exists in public directory
                if ($map->thumbnail && File::exists(public_path($map->thumbnail))) {
                    File::delete(public_path($map->thumbnail));
                }

                $image = $request->file('thumbnail');
                $filename = Str::random(32) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/maps'), $filename);
                $data['thumbnail'] = '/images/maps/' . $filename;
            }

            $map->update($data);
            $map->load(['thematique', 'user']);

            return $this->success($map, 'Carte mise à jour avec succès');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Carte non trouvée.', $e->getMessage(), 404);
        } catch (Exception $e) {
            return $this->error('Erreur lors de la mise à jour de la carte : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified map.
     */
    public function show($id)
    {
        try {
            $map = Map::with(['thematique', 'user'])->findOrFail($id);
            return $this->success($map, 'Détails de la carte récupérés avec succès');
        } catch (Exception $e) {
            return $this->error('Carte non trouvée ou erreur serveur.', $e->getMessage(), 404);
        }
    }

    /**
     * Remove the specified map from storage.
     */
    public function destroy($id)
    {
        try {
            $map = Map::findOrFail($id);

            // Delete thumbnail if it exists in public directory
            if ($map->thumbnail && File::exists(public_path($map->thumbnail))) {
                File::delete(public_path($map->thumbnail));
            }

            $map->delete();

            return $this->success(null, 'Carte supprimée avec succès');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Carte non trouvée.', $e->getMessage(), 404);
        } catch (Exception $e) {
            return $this->error('Erreur lors de la suppression de la carte : ' . $e->getMessage(), null, 500);
        }
    }
}

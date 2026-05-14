<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thematique;
use App\Http\Requests\Thematique\CreateThematiqueRequest;
use App\Http\Requests\Thematique\UpdateThematiqueRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class ThematiqueController extends Controller
{
    /**
     * Display a listing of thematiques.
     */
    public function index(): JsonResponse
    {
        try {
            $thematiques = Thematique::all();
            return $this->success($thematiques, 'Liste des thématiques récupérée avec succès');
        } catch (Exception $e) {
            return $this->error('Erreur lors de la récupération des thématiques : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created thematique.
     */
    public function store(CreateThematiqueRequest $request): JsonResponse
    {
        try {
            $thematique = Thematique::create($request->validated());
            return $this->success($thematique, 'Thématique créée avec succès', 201);
        } catch (Exception $e) {
            return $this->error('Erreur lors de la création de la thématique : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified thematique.
     */
    public function update(UpdateThematiqueRequest $request, $id): JsonResponse
    {
        try {
            $thematique = Thematique::findOrFail($id);
            $thematique->update($request->validated());
            return $this->success($thematique, 'Thématique mise à jour avec succès');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Thématique non trouvée.', $e->getMessage(), 404);
        } catch (Exception $e) {
            return $this->error('Erreur lors de la mise à jour de la thématique : ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified thematique from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $thematique = Thematique::findOrFail($id);
            $thematique->delete();
            return $this->success(null, 'Thématique supprimée avec succès');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Thématique non trouvée.', $e->getMessage(), 404);
        } catch (Exception $e) {
            return $this->error('Erreur lors de la suppression de la thématique : ' . $e->getMessage(), null, 500);
        }
    }
}

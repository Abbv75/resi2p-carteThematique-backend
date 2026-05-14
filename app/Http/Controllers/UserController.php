<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->error('Les identifiants fournis sont incorrects.', null, 401);
            }

            return $this->success($user->load('role'), 'Connexion réussie');
        } catch (\Exception $e) {
            return $this->error('Une erreur est survenue lors de la connexion.', $e->getMessage(), 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'site_web' => $request->site_web,
                'password' => Hash::make($request->password),
                'id_role' => $request->id_role,
            ]);

            return $this->success($user->load('role'), 'Utilisateur créé avec succès', 201);
        } catch (\Exception $e) {
            return $this->error('Une erreur est survenue lors de l\'inscription.', $e->getMessage(), 500);
        }
    }

    public function index()
    {
        try {
            $users = User::with('role')->get();
            return $this->success($users, 'Liste des utilisateurs récupérée avec succès');
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération des utilisateurs.', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->success($user->load('role'), 'Profil utilisateur');
        } catch (\Exception $e) {
            return $this->error('Utilisateur non trouvé ou erreur serveur.', $e->getMessage(), 404);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->validated();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return $this->success($user->load('role'), 'Utilisateur mis à jour avec succès');
        } catch (ModelNotFoundException $e) {
            return $this->error('Utilisateur non trouvé.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Une erreur est survenue lors de la mise à jour.', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->success(null, 'Utilisateur supprimé avec succès');
        } catch (ModelNotFoundException $e) {
            return $this->error('Utilisateur non trouvé.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Une erreur est survenue lors de la suppression.', $e->getMessage(), 500);
        }
    }

    public function getMyMaps($id)
    {
        try {
            $user = User::findOrFail($id);
            $maps = $user->maps()->with('thematique')->latest()->get();
            return $this->success($maps, 'Cartes de l\'utilisateur récupérées avec succès');
        } catch (ModelNotFoundException $e) {
            return $this->error('Utilisateur non trouvé.', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération des cartes.', $e->getMessage(), 500);
        }
    }
}

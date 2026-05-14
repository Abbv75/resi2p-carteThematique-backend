<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all();
            return $this->success($roles, 'Liste des rôles récupérée avec succès');
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération des rôles.', $e->getMessage(), 500);
        }
    }
}

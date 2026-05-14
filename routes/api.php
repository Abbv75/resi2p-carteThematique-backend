<?php

use App\Http\Controllers\ThematiqueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::post('/', [UserController::class, 'register']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::get('/{id}/maps', [UserController::class, 'getMyMaps']);
});

Route::get('/roles', [RoleController::class, 'index']);

Route::prefix('thematiques')->group(function () {
    Route::get('/', [ThematiqueController::class, 'index']);
    Route::post('/', [ThematiqueController::class, 'store']);
    Route::put('/{id}', [ThematiqueController::class, 'update']);
    Route::delete('/{id}', [ThematiqueController::class, 'destroy']);
});

Route::prefix('maps')->group(function () {
    Route::get('/', [MapController::class, 'index']);
    Route::get('/{id}', [MapController::class, 'show']);
    Route::post('/', [MapController::class, 'store']);
    Route::put('/{id}', [MapController::class, 'update']);
    Route::delete('/{id}', [MapController::class, 'destroy']);
});

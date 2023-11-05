<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

// ####################
// Routes publiques (accessibles sans authentification)
// ####################

// Routes d'authentification
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

// Routes de consultation des lieu
Route::get('/place', [PlaceController::class, 'index']);
Route::get('/place/{id}', [PlaceController::class, 'show']);

// ####################
// Routes pour les utilisateurs authentifiés
// ####################

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes pour la gestion du profil utilisateur
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/archive', [UserController::class, 'archive']);



});

// ####################
// Routes pour les administrateurs
// ####################

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // Routes pour la gestion des utilisateurs
    Route::get('/users', [UserController::class, 'index']);                // Liste de tous les utilisateurs
    Route::get('/archived-users', [UserController::class, 'archivedUsers']);    // Liste des utilisateurs archivés
    Route::delete('/users/{id}/destroy', [UserController::class, 'destroy']);      // Supprimer un compte d'utilisateur définitivement
    Route::get('/archived-users/{id}', [UserController::class, 'showArchivedUser']); // Voir un utilisateur archivé spécifique
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);       // Restaurer un utilisateur archivé

    // Routes pour la gestion des lieu
    Route::post('/place', [PlaceController::class, 'store']);         // Ajouter un lieu
    Route::put('/place/{id}', [PlaceController::class, 'update']);    // Modifier un lieu
    Route::delete('/place/{id}', [PlaceController::class, 'destroy']); // Supprimer un lieu
    Route::get('/archived-place', [PlaceController::class, 'archivedPlace']); // Liste des lieux archivés
    Route::get('/archived-place/{id}', [PlaceController::class, 'showArchivedLieu']); // Voir un lieu archivé spécifique
    Route::post('/place/{id}/restore', [PlaceController::class, 'restore']); // Restaurer un lieu archivé
    //Route::post('/place/{id}/archive', [PlaceController::class, 'archive']); // archive un lieu


});


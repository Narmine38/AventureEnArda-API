<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LieuxController;
use App\Http\Controllers\UserController;
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
Route::post('/register', [UserController::class, 'store']); // Enregistrer un nouvel utilisateur


// Routes de consultation des lieux
Route::get('/lieux', [LieuxController::class, 'index']);        // Liste de tous les lieux
Route::get('/lieux/{id}', [LieuxController::class, 'show']);    // Détail d'un lieu








// ####################
// Routes pour les utilisateurs authentifiés
// ####################

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Routes pour la gestion du profil utilisateur
    Route::get('/users/{id}', [UserController::class, 'show']);       // Voir son profil
    Route::put('/users/{id}', [UserController::class, 'update']);     // Modifier son profil

    // Route pour archiver son propre utilisateur
    Route::post('/users/{id}/archive', [UserController::class, 'archive']);       // Archiver son propre utilisateur
});

// ####################
// Routes pour les administrateurs
// ####################

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // Routes pour la gestion des utilisateurs
    Route::get('/users', [UserController::class, 'index']);                // Liste de tous les utilisateurs
    Route::delete('/users/{id}', [UserController::class, 'destroy']);      // Supprimer un compte d'utilisateur définitivement
    Route::get('/archived-users', [UserController::class, 'archivedUsers']);    // Liste des utilisateurs archivés
    Route::get('/archived-users/{id}', [UserController::class, 'showArchivedUser']); // Voir un utilisateur archivé spécifique
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);       // Restaurer un utilisateur archivé
    Route::post('/users/{id}/archive', [UserController::class, 'archive']);       // Restaurer un utilisateur archivé



    // Routes pour la gestion des lieux
    Route::post('/lieux', [LieuxController::class, 'store']);         // Ajouter un lieu
    Route::put('/lieux/{id}', [LieuxController::class, 'update']);    // Modifier un lieu
    Route::delete('/lieux/{id}', [LieuxController::class, 'destroy']); // Supprimer un lieu
    Route::get('/archived-lieux', [LieuxController::class, 'archivedLieu']); // Liste des lieux archivés
    Route::get('/archived-lieux/{id}', [LieuxController::class, 'showArchivedLieu']); // Voir un lieux archivé spécifique
    Route::post('/lieux/{id}/restore', [LieuxController::class, 'restore']);// Restaurer un lieux archivé
    Route::post('/lieux/{id}/archive', [LieuxController::class, 'archive']);



});



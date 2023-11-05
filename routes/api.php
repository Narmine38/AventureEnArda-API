<?php

use App\Http\Controllers\AuthController;
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
Route::post('/register', [UserController::class, 'store']); // Enregistrer un nouvel utilisateur

// ####################
// Routes pour les utilisateurs authentifiés
// ####################

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



    // Routes pour la gestion du profil utilisateur
    Route::get('/users/{id}', [UserController::class, 'show']);       // Voir son profil
    Route::put('/users/{id}', [UserController::class, 'update']);     // Modifier son profil
    Route::post('/users/{id}/archive', [UserController::class, 'archive']);       // Archiver son propre utilisateur



});

// ####################
// Routes pour les administrateurs
// ####################

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // Routes pour la gestion des utilisateurs
    Route::get('/users', [UserController::class, 'index']);                // Liste de tous les utilisateurs
    Route::get('/archived-users', [UserController::class, 'archivedUsers']);    // Liste des utilisateurs archivés
    Route::delete('/users/{id}', [UserController::class, 'destroy']);      // Supprimer un compte d'utilisateur définitivement
    Route::get('/archived-users/{id}', [UserController::class, 'showArchivedUser']); // Voir un utilisateur archivé spécifique
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);       // Restaurer un utilisateur archivé


});


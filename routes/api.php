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


// Routes de consultation des lieux
Route::get('/lieux', [LieuxController::class, 'index']);        // Liste de tous les lieux
Route::get('/lieux/{id}', [LieuxController::class, 'show']);    // Détail d'un lieu

Route::get('/hebergements', [HebergementController::class, 'index']);
Route::get('/hebergements/{id}', [HebergementController::class, 'show']);

// Routes de consultation des activités
Route::get('/activites', [ActiviteController::class, 'index']);        // Liste de toutes les activités
Route::get('/activites/{id}', [ActiviteController::class, 'show']);    // Détail d'une activité

// Routes de consultation des personnages
Route::get('/personnages', [PersonnageController::class, 'index']);        // Liste de tous les personnages
Route::get('/personnages/{id}', [PersonnageController::class, 'show']);    // Détail d'un personnage


Route::post('/reservations/calculate-price', [ReservationController::class, 'calculatePrice']);






// ####################
// Routes pour les utilisateurs authentifiés
// ####################

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes pour la gestion du profil utilisateur
    Route::get('/users/{id}', [UserController::class, 'show']);       // Voir son profil
    Route::put('/users/{id}', [UserController::class, 'update']);     // Modifier son profil
    Route::post('/users/{id}/archive', [UserController::class, 'archive']);       // Archiver son propre utilisateur

// Routes pour la gestion des réservations
    Route::post('/reservations', [ReservationController::class, 'store']);         // Créer une réservation
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);      // Voir une réservation
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);    // Modifier une réservation
    Route::post('/reservations/{id}/archive', [ReservationController::class, 'archive']);       // Restaurer un utilisateur archivé
    Route::get('/reservations/user/{userId}', [ReservationController::class, 'getUserReservations']);


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

    // Routes pour la gestion des lieux
    Route::post('/lieux', [LieuxController::class, 'store']);         // Ajouter un lieu
    Route::put('/lieux/{id}', [LieuxController::class, 'update']);    // Modifier un lieu
    Route::delete('/lieux/{id}', [LieuxController::class, 'destroy']); // Supprimer un lieu
    Route::get('/archived-lieux', [LieuxController::class, 'archivedLieu']); // Liste des lieux archivés
    Route::get('/archived-lieux/{id}', [LieuxController::class, 'showArchivedLieu']); // Voir un lieux archivé spécifique
    Route::post('/lieux/{id}/restore', [LieuxController::class, 'restore']);// Restaurer un lieux archivé
    Route::post('/lieux/{id}/archive', [LieuxController::class, 'archive']);

    // Routes pour la gestion des hébergements
    Route::post('/hebergements', [HebergementController::class, 'store']);         // Ajouter un hébergement
    Route::put('/hebergements/{id}', [HebergementController::class, 'update']);    // Modifier un hébergement
    Route::delete('/hebergements/{id}', [HebergementController::class, 'destroy']); // Supprimer un hébergement
    Route::get('/hebergements-archived', [HebergementController::class, 'archivedHebergement']);
    Route::get('/hebergements-archived/{id}', [HebergementController::class, 'showArchivedHebegement']);
    Route::post('/hebergements/{id}/archive', [HebergementController::class, 'archive']);
    Route::post('/hebergements/{id}/restore', [HebergementController::class, 'restore']);

    // Routes pour la gestion des activités
    Route::post('/activites', [ActiviteController::class, 'store']);         // Ajouter une activité
    Route::put('/activites/{id}', [ActiviteController::class, 'update']);    // Modifier une activité
    Route::delete('/activites/{id}', [ActiviteController::class, 'destroy']); // Supprimer une activité
    Route::get('/activites-archived', [ActiviteController::class, 'archivedActivite']);
    Route::get('/activites-archived/{id}', [ActiviteController::class, 'showArchivedActivite']);
    Route::post('/activites/{id}/archive', [ActiviteController::class, 'archive']);
    Route::post('/activites/{id}/restore', [ActiviteController::class, 'restore']);

    // Routes pour la gestion des personnages
    Route::post('/personnages', [PersonnageController::class, 'store']);         // Ajouter un personnage
    Route::put('/personnages/{id}', [PersonnageController::class, 'update']);    // Modifier un personnage
    Route::delete('/personnages/{id}', [PersonnageController::class, 'destroy']); // Supprimer un personnage
    Route::get('/personnages-archived', [PersonnageController::class, 'archivedPersonnage']);
    Route::get('/personnages-archived/{id}', [PersonnageController::class, 'showArchivedPersonnage']);
    Route::post('/personnages/{id}/archive', [PersonnageController::class, 'archive']);
    Route::post('/personnages/{id}/restore', [PersonnageController::class, 'restore']);

    Route::get('/reservations', [ReservationController::class, 'index']); // Liste de toutes les réservations
    Route::get('/reservations-archived', [ReservationController::class, 'archivedReservation']);
    Route::get('/reservations-archived/{id}', [ReservationController::class, 'showArchivedReservation']);
    Route::post('/reservations/{id}/restore', [ReservationController::class, 'restore']);



});


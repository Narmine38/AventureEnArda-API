<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReservationController;
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

Route::get('/accommodation', [AccommodationController::class, 'index']);
Route::get('/accommodation/{id}', [AccommodationController::class, 'show']);

// Routes de consultation des activités
Route::get('/activities', [ActiviteController::class, 'index']);        // Liste de toutes les activités
Route::get('/activity/{id}', [ActiviteController::class, 'show']);    // Détail d'une activité

// Routes de consultation des personnages
Route::get('/characters', [CharacterController::class, 'index']);        // Liste de tous les personnages
Route::get('/character/{id}', [CharacterController::class, 'show']);    // Détail d'un personnage

// ####################
// Routes pour les utilisateurs authentifiés
// ####################

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes pour la gestion du profil utilisateur
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/archive', [UserController::class, 'archive']);

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
    Route::get('/archived-users', [UserController::class, 'archivedUsers']);    // Liste des utilisateurs archivés
    Route::delete('/users/{id}/destroy', [UserController::class, 'destroy']);      // Supprimer un compte d'utilisateur définitivement
    Route::get('/archived-users/{id}', [UserController::class, 'showArchivedUser']); // Voir un utilisateur archivé spécifique
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);       // Restaurer un utilisateur archivé

    // Routes pour la gestion des lieu
    Route::post('/place', [PlaceController::class, 'store']);         // Ajouter un lieu
    Route::put('/place/{id}', [PlaceController::class, 'update']);    // Modifier un lieu
    Route::delete('/place/{id}', [PlaceController::class, 'destroy']); // Supprimer un lieu
    Route::get('/archived-place', [PlaceController::class, 'archivedPlace']); // Liste des lieux archivés
    Route::get('/archived-place/{id}', [PlaceController::class, 'showArchivedPlace']); // Voir un lieu archivé spécifique
    Route::post('/place/{id}/restore', [PlaceController::class, 'restore']); // Restaurer un lieu archivé
    Route::post('/place/{id}/archive', [PlaceController::class, 'archive']); // archive un lieu

    // Routes pour la gestion des hébergements
    Route::post('/accommodation', [AccommodationController::class, 'store']);         // Ajouter un hébergement
    Route::put('/accommodation/{id}', [AccommodationController::class, 'update']);    // Modifier un hébergement
    Route::delete('/accommodation/{id}', [AccommodationController::class, 'destroy']); // Supprimer un hébergement
    Route::get('/accommodation-archived', [AccommodationController::class, 'archivedAccommodation']);
    Route::get('/accommodation-archived/{id}', [AccommodationController::class, 'showArchivedAccommodation']);
    Route::post('/accommodation/{id}/archive', [AccommodationController::class, 'archive']);
    Route::post('/accommodation/{id}/restore', [AccommodationController::class, 'restore']);

    // Routes pour la gestion des activités
    Route::post('/activity', [ActiviteController::class, 'store']);         // Ajouter une activité
    Route::put('/activity/{id}', [ActiviteController::class, 'update']);    // Modifier une activité
    Route::delete('/activity/{id}', [ActiviteController::class, 'destroy']); // Supprimer une activité
    Route::get('/activities-archived', [ActiviteController::class, 'archivedActivity']);
    Route::get('/activity-archived/{id}', [ActiviteController::class, 'showArchivedActivity']);
    Route::post('/activity/{id}/archive', [ActiviteController::class, 'archive']);
    Route::post('/activity/{id}/restore', [ActiviteController::class, 'restore']);

    // Routes pour la gestion des personnages
    Route::post('/character', [CharacterController::class, 'store']);         // Ajouter un personnage
    Route::put('/character/{id}', [CharacterController::class, 'update']);    // Modifier un personnage
    Route::delete('/character/{id}', [CharacterController::class, 'destroy']); // Supprimer un personnage
    Route::get('/characters-archived', [CharacterController::class, 'archivedCharacters']);
    Route::get('/character-archived/{id}', [CharacterController::class, 'showArchivedCharacter']);
    Route::post('/character/{id}/archive', [CharacterController::class, 'archive']);
    Route::post('/character/{id}/restore', [CharacterController::class, 'restore']);

    Route::get('/reservations', [ReservationController::class, 'index']); // Liste de toutes les réservations
    Route::get('/reservations-archived', [ReservationController::class, 'archivedReservation']);
    Route::get('/reservations-archived/{id}', [ReservationController::class, 'showArchivedReservation']);
    Route::post('/reservations/{id}/restore', [ReservationController::class, 'restore']);


});


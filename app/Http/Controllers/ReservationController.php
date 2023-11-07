<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Activite;
use App\Models\Character;
use App\Models\Place;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ReservationController extends Controller
{
    /**
     * Affiche la liste des réservations.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $reservations = Reservation::with(['user', 'place', 'accommodation', 'activity', 'character'])->get();
        return response()->json($reservations);
    }

    /**
     * Enregistre une nouvelle réservation dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'place_id' => 'required|integer',
            'accommodation_id' => 'required|integer',
            'activity_id' => 'required|integer',
            'character_id' => 'required|integer',
            'arrival_date' => 'required|date',
            'starting_date' => 'required|date',
            'number_of_people' => 'required|integer',
            'statut' => 'required|string'
        ]);

        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 403);
        }

        $reservation = Reservation::create($data);
        $reservation->load(['place', 'accommodation', 'activity', 'character']);

        // Sending an email to the user with reservation details is typically handled in an event listener or job for better separation of concerns.
        // Here, for simplification, we will assume that you have an event or job set up for this purpose.

        return response()->json($reservation, 201);
    }


    /**
     * Affiche une réservation spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id)
    {
        $reservation = Reservation::with(['user', 'place', 'accommodation', 'activity', 'character'])->findOrFail($id);
        return response()->json($reservation, 200);
    }

    /**
     * Met à jour une réservation spécifique.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        $rules = [
            'user_id' => 'integer',
            'place_id' => 'integer',
            'accommodation_id' => 'integer',
            'activity_id' => 'integer',
            'character_id' => 'integer',
            'arrival_date' => 'date',
            'starting_date' => 'date',
            'number_of_people' => 'integer',
            'statut' => 'string'
        ];
// revoir role ici
        if (auth()->check() && auth()->user()->getRoleNames() === 'admin') {
            $rules['price'] = 'numeric';
        }

        $data = $request->validate($rules);
        $reservation->update($data);

        return response()->json($reservation, 200);
    }

    /**
     * Supprime une réservation spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // Essayez d'abord de trouver la réservation, y compris ceux qui sont "soft deleted".
        $reservation = Reservation::withTrashed()->findOrFail($id);

        // Supprimez définitivement l'hebergment.
        $reservation->forceDelete();

        return response()->json([
            'message' => 'réservation deleted permanently!'
        ], 204);
    }

    /**
     * Archive (soft delete) une réservation spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        $reservation = Reservation::findOrFail($id);

        // Si la réservation n'est pas déjà "soft deleted", on le "soft delete"
        if (!$reservation->trashed()) {
            $reservation->delete();
            return response()->json([
                'message' => 'réservation archived successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'réservation is already archived.'
            ], 400);
        }
    }

    /**
     * Restaure une réservation qui a été "soft deleted".
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // Récupère les réservations "soft deleted" seulement
        $reservation = Reservation::onlyTrashed()->findOrFail($id);

        // Si la réservation est "soft deleted", on le restaure
        if ($reservation->trashed()) {
            $reservation->restore();
            return response()->json([
                'message' => 'réservation restored successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'réservation is not archived.'
            ], 400);
        }
    }

    /**
     * Affiche la liste des réservations qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedReservation(): JsonResponse
    {
        // Récupère uniquement les réservations qui sont "soft deleted" avec leur relation "place"
        $reservation = Reservation::onlyTrashed()->with(['user', 'place', 'accommodation', 'activity', 'character'])->get();

        return response()->json(['data' => $reservation]);
    }

    /**
     * Récupère et affiche les détails d'une réservation archivé spécifique par son ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function showArchivedReservation(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les réservation archivés
        $reservation = Reservation::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $reservation]);
    }

    public function getUserReservations($userId)
    {
        $reservations = Reservation::where('user_id', $userId)->get();
        return response()->json($reservations);
    }




}

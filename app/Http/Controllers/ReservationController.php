<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Activite;
use App\Models\Character;
use App\Models\Place;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DateTime;

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
        // Validate the request data
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'place_id' => 'required|integer',
            'accommodation_id' => 'required|integer',
            'activity_id' => 'required|integer',
            'character_id' => 'required|integer',
            'arrival_date' => 'required|date',
            'starting_date' => 'required|date',
            'number_of_people' => 'required|integer',
            'statut' => 'required|string',
            // 'price' field is not directly submitted by user but calculated from 'accommodation_id'
        ]);

        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 403);
        }

        // Retrieve the accommodation price from the database using the 'accommodation_id'
        $accommodation = Accommodation::find($validatedData['accommodation_id']);
        if (!$accommodation) {
            return response()->json(['message' => 'Accommodation not found'], 404);
        }

        // Calculate the number of nights for the stay
        $nights = (new \DateTime($validatedData['arrival_date']))->diff(new \DateTime($validatedData['starting_date']))->days;

        // Calculate the price
        $price = $accommodation->price * $validatedData['number_of_people'] * $nights;

        // Include the price in the data to be stored
        $validatedData['price'] = $price;

        // Create the reservation with the calculated price
        $reservation = Reservation::create($validatedData);

        // Load related models
        $reservation->load(['place', 'accommodation', 'activity', 'character']);

        // Return the reservation data along with the status code 201
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

        $data = $request->validate([
            'user_id' => 'sometimes|integer',
            'place_id' => 'sometimes|integer',
            'accommodation_id' => 'sometimes|integer',
            'activity_id' => 'sometimes|integer',
            'character_id' => 'sometimes|integer',
            'arrival_date' => 'sometimes|date',
            'starting_date' => 'sometimes|date',
            'number_of_people' => 'sometimes|integer',
            'statut' => 'sometimes|string',
        ]);

        // Update the reservation with new data without changing the price
        $reservation->update($data);

        return response()->json($reservation, 200);
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

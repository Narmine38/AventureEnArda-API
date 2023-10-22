<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Hebergement;
use App\Models\Lieux;
use App\Models\Personnage;
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
        $reservations = Reservation::with(['user', 'lieu', 'hebergement', 'activite', 'personnage'])->get();

        // Ajoutez le prix à chaque réservation
        foreach ($reservations as $reservation) {
            $reservation->prix = $reservation->price;  // Utilisez l'accessor pour obtenir le prix
        }

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
            'lieu_id' => 'required|integer',
            'hebergement_id' => 'required|integer',
            'activite_id' => 'required|integer',
            'personnage_id' => 'required|integer',
            'date_arrivee' => 'required|date',
            'date_depart' => 'required|date',
            'nombre_personnes' => 'required|integer',
            'statut' => 'required|string'
        ]);

        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 403);
        }

        $reservation = Reservation::create($data);

        // Récupérez les détails de la réservation
        $lieu = Lieux::find($data['lieu_id']);
        $hebergement = Hebergement::find($data['hebergement_id']);
        $activite = Activite::find($data['activite_id']);
        $personnage = Personnage::find($data['personnage_id']);

        // Calculez le prix total (ajustez selon votre logique de calcul)
        $nights = (new \DateTime($data['date_arrivee']))->diff(new \DateTime($data['date_depart']))->days;
        $totalPrice = $hebergement->prix * $nights * $data['nombre_personnes'];

        // Formatez l'information pour l'e-mail
        $emailContent = "
    <strong>Récapitulatif de votre réservation :</strong><br>
    Lieu : {$lieu->nom}<br>
    Hébergement : {$hebergement->nom}<br>
    Activité : $activite->nom<br>
    Personnage : {$personnage->nom}<br>
    Date d'arrivée : {$data['date_arrivee']}<br>
    Date de départ : {$data['date_depart']}<br>
    Nombre de personnes : {$data['nombre_personnes']}<br>
    Statut : {$data['statut']}<br>
    <strong>Prix total : {$totalPrice}€</strong>
    ";

        // Envoyez l'e-mail avec le contenu formaté
        $toEmail = auth()->user()->email; // Email de l'utilisateur connecté
        $subject = "Confirmation de votre réservation";
        $textBody = "Votre réservation a été confirmée!";

        $emailController = new EmailController();
        $emailController->sendEmail($toEmail, $subject, $emailContent, $textBody);

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
        $reservation = Reservation::findOrFail($id)->load(['user', 'lieu', 'hebergement', 'activite', 'personnage']);
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
            'lieu_id' => 'integer',
            'hebergement_id' => 'integer',
            'activite_id' => 'integer',
            'personnage_id' => 'integer',
            'date_arrivee' => 'date',
            'date_depart' => 'date',
            'nombre_personnes' => 'integer',
            'statut' => 'string'
        ];
// revoir role ici
        if (auth()->check() && auth()->user()->role === 'admin') {
            $rules['prix'] = 'numeric';
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
        // Récupère uniquement les réservations qui sont "soft deleted" avec leur relation "lieu"
        $reservation = Reservation::onlyTrashed()->with(['user', 'lieu', 'hebergement', 'activite', 'personnage'])->get();

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

    /**
     * @throws \Exception
     */
    public function calculatePrice(Request $request): JsonResponse
    {
        $nights = (new \DateTime($request->date_arrivee))->diff(new \DateTime($request->date_depart))->days;

        $hebergement = Hebergement::findOrFail($request->hebergement_id);
        $hebergementCost = $hebergement->prix * $nights * $request->nombre_personnes;

        return response()->json(['total' => $hebergementCost]);
    }
    public function getUserReservations($userId)
    {
        $reservations = Reservation::where('user_id', $userId)->get();
        return response()->json($reservations);
    }




}

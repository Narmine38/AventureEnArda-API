<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Place;

class PlaceController extends Controller
{
    /**
     * Récupère et renvoie la liste de tous les lieux.
     *
     * @return JsonResponse La réponse contenant tous les lieux.
     */
    public function index(): JsonResponse
    {
        $places = Place::all(); // Obtient tous les lieux de la base de données.
        return response()->json($places, 200); // Renvoie la liste des lieux avec un statut HTTP 200.
    }

    /**
     * Valide et stocke un nouveau lieu dans la base de données.
     *
     * @param Request $request La requête HTTP contenant les données du lieu.
     * @return JsonResponse La réponse après l'insertion du lieu.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'shortDescription' => 'required|string',
            'longDescription' => 'required|string',
            'locationType' => 'required|string',
            'restrictions' => 'required|string',
            'travelAdvice' => 'required|string',
            'picture' => 'required|string', // Validez comme une chaîne, si vous gérez les fichiers différemment, modifiez ceci.
            'story' => 'nullable|string'
        ]);

        $place = Place::create($data); // Crée et enregistre le nouveau lieu avec les données validées.

        return response()->json($place, 201); // Renvoie les données du lieu créé avec un statut HTTP 201.
    }

    /**
     * Récupère et renvoie les détails d'un lieu spécifique.
     *
     * @param int $id L'identifiant du lieu.
     * @return JsonResponse La réponse contenant les détails du lieu.
     */
    public function show($id): JsonResponse
    {
        $place = Place::findOrFail($id); // Trouve le lieu par son ID ou échoue avec une erreur 404.
        return response()->json($place, 200); // Renvoie les détails du lieu avec un statut HTTP 200.
    }

    /**
     * Valide et met à jour un lieu spécifique dans la base de données.
     *
     * @param Request $request La requête HTTP contenant les données à mettre à jour.
     * @param int $id L'identifiant du lieu à mettre à jour.
     * @return JsonResponse La réponse après la mise à jour du lieu.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'shortDescription' => 'sometimes|required|string',
            'longDescription' => 'sometimes|required|string',
            'locationType' => 'sometimes|required|string',
            'restrictions' => 'sometimes|required|string',
            'travelAdvice' => 'sometimes|required|string',
            'picture' => 'sometimes|required|string',
            'story' => 'nullable|string'
        ]);

        $place = Place::findOrFail($id); // Trouve le lieu par son ID ou échoue avec une erreur 404.
        $place->update($data); // Met à jour le lieu avec les données validées.

        return response()->json($place, 200); // Renvoie les données du lieu mis à jour avec un statut HTTP 200.
    }

    /**
     * Supprime un lieu spécifique de la base de données après l'avoir retrouvé.
     *
     * @param int $id L'identifiant du lieu.
     * @return JsonResponse La réponse après suppression du lieu.
     */
    public function destroy($id): JsonResponse
    {
        $place = Place::withTrashed()->findOrFail($id); // Trouve le lieu, y compris ceux archivés.
        $place->forceDelete(); // Supprime définitivement le lieu.

        return response()->json(['message' => 'Le lieu a été supprimé définitivement !'], 204); // Confirmation de suppression.
    }

    /**
     * Archive (soft delete) un lieu spécifique.
     *
     * @param int $id L'identifiant du lieu à archiver.
     * @return JsonResponse La réponse après l'archivage du lieu.
     */
    public function archive($id): JsonResponse
    {
        $place = Place::findOrFail($id); // Trouve le lieu par son ID ou échoue avec une erreur 404.

        // Vérifie si le lieu n'est pas déjà archivé et l'archive si nécessaire.
        if (!$place->trashed()) {
            $place->delete(); // Archive le lieu (soft delete).
            return response()->json(['message' => 'Le lieu a été archivé avec succès !']);
        } else {
            return response()->json(['message' => 'Le lieu est déjà archivé.'], 400); // Le lieu est déjà archivé.
        }
    }

    /**
     * Restaure un lieu qui a été "soft deleted".
     *
     * @param int $id L'identifiant du lieu à restaurer.
     * @return JsonResponse La réponse après la restauration du lieu.
     */
    public function restore($id): JsonResponse
    {
        // Récupère les lieux archivés (soft deleted) uniquement.
        $place = Place::onlyTrashed()->findOrFail($id); // Trouve le lieu archivé par son ID ou échoue avec une erreur 404.

        // Restaure le lieu s'il est archivé.
        if ($place->trashed()) {
            $place->restore(); // Restaure le lieu.
            return response()->json(['message' => 'Le lieu a été restauré avec succès !']);
        } else {
            return response()->json(['message' => "Le lieu n'est pas archivé et ne peut donc pas être restauré."], 400);
        }
    }

    /**
     * Affiche la liste des lieux qui ont été archivés (soft deleted).
     *
     * @return JsonResponse La réponse contenant la liste des lieux archivés.
     */
    public function archivedPlace(): JsonResponse
    {
        $archivedPlaces = Place::onlyTrashed()->get(); // Obtient uniquement les lieux archivés.
        return response()->json(['data' => $archivedPlaces]); // Renvoie la liste des lieux archivés avec un statut HTTP 200.
    }

    /**
     * Récupère et affiche les détails d'un lieu archivé spécifique par son ID.
     *
     * @param int $id L'identifiant du lieu archivé.
     * @return JsonResponse La réponse contenant les détails du lieu archivé.
     */
    public function showArchived($id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les lieux archivés.
        $archivedPlace = Place::onlyTrashed()->findOrFail($id); // Trouve le lieu archivé par son ID ou échoue avec une erreur 404.
        return response()->json(['data' => $archivedPlace]); // Renvoie les détails du lieu archivé avec un statut HTTP 200.
    }

}

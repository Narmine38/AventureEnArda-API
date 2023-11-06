<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    /**
     * Récupère et renvoie la liste de tous les hébergements avec leur lieu associé.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $accommodations = Accommodation::with('place')->get();
        return response()->json($accommodations, 200);
    }

    /**
     * Valide les données entrantes et crée un nouvel hébergement dans la base de données.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric',
            'picture' => 'required|string', // Assurez-vous de valider correctement si 'picture' est un fichier
            'story' => 'nullable|string',
            'place_id' => 'required|integer|exists:places,id'
        ]);

        $accommodation = Accommodation::create($data);

        return response()->json($accommodation, 201);
    }

    /**
     * Récupère et renvoie les détails d'un hébergement spécifique.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $accommodation = Accommodation::with('place')->findOrFail($id);
        return response()->json($accommodation, 200);
    }

    /**
     * Valide les données entrantes et met à jour un hébergement spécifique dans la base de données.
     *
     * @param Request $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'picture' => 'sometimes|required|string',
            'story' => 'nullable|string',
            'place_id' => 'sometimes|required|integer|exists:places,id'
        ]);

        $accommodation = Accommodation::findOrFail($id);
        $accommodation->update($data);

        return response()->json($accommodation, 200);
    }

    /**
     * Supprime un hébergement spécifique de la base de données après l'avoir retrouvé.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $accommodation = Accommodation::withTrashed()->findOrFail($id);
        $accommodation->forceDelete();

        return response()->json(['message' => 'Hébergement supprimé définitivement !'], 204);
    }

    /**
     * Archive (soft delete) un hébergement spécifique.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function archive(int $id): JsonResponse
    {
        $accommodation = Accommodation::findOrFail($id);

        if (!$accommodation->trashed()) {
            $accommodation->delete();
            return response()->json(['message' => 'Hébergement archivé avec succès !'], 200);
        } else {
            return response()->json(['message' => 'Cet hébergement est déjà archivé.'], 400);
        }
    }

    /**
     * Restaure un hébergement qui a été archivé (soft deleted).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $accommodation = Accommodation::onlyTrashed()->findOrFail($id);
        $accommodation->restore();

        return response()->json(['message' => 'Hébergement restauré avec succès !'], 200);
    }

    /**
     * Affiche la liste des hébergements qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedAccommodation(): JsonResponse
    {
        $archivedAccommodations = Accommodation::onlyTrashed()->with('place')->get();
        return response()->json(['data' => $archivedAccommodations], 200);
    }

    /**
     * Récupère et affiche les détails d'un hébergement archivé spécifique par son ID.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function showArchivedAccommodation(int $id): JsonResponse
    {
        $accommodation = Accommodation::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $accommodation], 200);
    }
}

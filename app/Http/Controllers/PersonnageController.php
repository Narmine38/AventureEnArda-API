<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Personnage;

class PersonnageController extends Controller
{
    /**
     * Récupère et renvoie la liste de tous les personnages.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $personnage = Personnage::with('lieu')->get();
        return response()->json($personnage, 200);
    }

    /**
     * Valide les données entrantes et crée un nouveau personnage dans la base de données.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'histoire' => 'required|string',
            'photo' => 'required|string',
            'lieu_id' => 'required|integer|exists:lieux,id'
        ]);

        $personnage = Personnage::create($data);

        return response()->json($personnage, 201);
    }

    /**
     * Récupère et renvoie les détails d'un personnage spécifique.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $personnage = Personnage::findOrFail($id);
        return response()->json($personnage, 200);
    }

    /**
     * Valide les données entrantes et met à jour un personnage spécifique dans la base de données.
     *
     * @param Request $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'histoire' => 'sometimes|required|string',
            'photo' => 'sometimes|required|string',
            'lieu_id' => 'sometimes|required|integer|exists:lieux,id'
        ]);

        $personnage = Personnage::findOrFail($id);
        $personnage->update($data);

        return response()->json($personnage, 200);
    }

    /**
     * Supprime un personnage spécifique de la base de données après l'avoir retrouvé.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // Essayez d'abord de trouver le personnage, y compris ceux qui sont "soft deleted".
        $personnage = Personnage::withTrashed()->findOrFail($id);

        // Supprimez définitivement du personnage.
        $personnage->forceDelete();

        return response()->json([
            'message' => 'personnage deleted permanently!'
        ], 204);
    }

    /**
     * Archive (soft delete) un personnage spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        $personnage = Personnage::findOrFail($id);

        // Si le personnage n'est pas déjà "soft deleted", on le "soft delete"
        if (!$personnage->trashed()) {
            $personnage->delete();
            return response()->json([
                'message' => 'personnage archived successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'personnage is already archived.'
            ], 400);
        }
    }

    /**
     * Restaure un personnage qui a été "soft deleted".
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // Récupère le personnage "soft deleted" seulement
        $personnage = Personnage::onlyTrashed()->findOrFail($id);

        // Si le personnage est "soft deleted", on le restaure
        if ($personnage->trashed()) {
            $personnage->restore();
            return response()->json([
                'message' => 'personnage restored successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'personnage is not archived.'
            ], 400);
        }
    }

    /**
     * Affiche la liste des personnages qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedPersonnage(): JsonResponse
    {
        // Récupère uniquement les personnages qui sont "soft deleted" avec leur relation "lieu"
        $personnage = Personnage::onlyTrashed()->with('lieu')->get();
        return response()->json(['data' => $personnage]);
    }

    /**
     * Récupère et affiche les détails d'un personnage archivé spécifique par son ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function showArchivedPersonnage(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les personnages archivés
        $personnage = Personnage::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $personnage]);
    }
}

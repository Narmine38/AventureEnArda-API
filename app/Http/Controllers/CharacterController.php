<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    /**
     * Récupère et renvoie la liste de tous les personnages.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $character = Character::with('place')->get();
        return response()->json($character, 200);
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
            'name' => 'required|string|max:255',
            'story' => 'required|string',
            'picture' => 'required|string',
            'place_id' => 'required|integer|exists:places,id'
        ]);

        $character = Character::create($data);

        return response()->json($character, 201);
    }

    /**
     * Récupère et renvoie les détails d'un personnage spécifique.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $character = Character::findOrFail($id);
        return response()->json($character, 200);
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
            'name' => 'sometimes|required|string|max:255',
            'story' => 'sometimes|required|string',
            'picture' => 'sometimes|required|string',
            'place_id' => 'sometimes|required|integer|exists:places,id'
        ]);

        $character = Character::findOrFail($id);
        $character->update($data);

        return response()->json($character, 200);
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
        $character = Character::withTrashed()->findOrFail($id);

        // Supprimez définitivement du personnage.
        $character->forceDelete();

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
        $character = Character::findOrFail($id);

        // Si le personnage n'est pas déjà "soft deleted", on le "soft delete"
        if (!$character->trashed()) {
            $character->delete();
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
        $character = Character::onlyTrashed()->findOrFail($id);

        // Si le personnage est "soft deleted", on le restaure
        if ($character->trashed()) {
            $character->restore();
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
    public function archivedCharacters(): JsonResponse
    {
        // Récupère uniquement les personnages qui sont "soft deleted" avec leur relation "lieu"
        $character = Character::onlyTrashed()->with('lieu')->get();
        return response()->json(['data' => $character]);
    }

    /**
     * Récupère et affiche les détails d'un personnage archivé spécifique par son ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function showArchivedCharacter(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les personnages archivés
        $character = Character::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $character]);
    }
}

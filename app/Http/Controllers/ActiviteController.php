<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Activite;

class ActiviteController extends Controller
{
    /**
     * Récupère et renvoie la liste de toutes les activités.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $activites = Activite::with('place')->get();
        return response()->json($activites, 200);
    }

    /**
     * Valide les données entrantes et crée une nouvelle activité dans la base de données.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'sometimes|required|string', // Si 'photo' est un fichier, modifiez cette validation en conséquence
            'type' => 'required|string',
            'age_range' => 'required|string',
            'place_id' => 'required|integer|exists:places,id'
        ]);

        $activite = Activite::create($data);

        return response()->json($activite, 201);
    }

    /**
     * Récupère et renvoie les détails d'une activité spécifique.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);
        return response()->json($activite, 200);
    }

    /**
     * Valide les données entrantes et met à jour une activité spécifique dans la base de données.
     *
     * @param Request $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'picture' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'age_range' => 'sometimes|required|string',
            'place_id' => 'required|integer|exists:places,id'
        ]);

        $activite = Activite::findOrFail($id);
        $activite->update($data);

        return response()->json($activite, 200);
    }

    /**
     * Supprime une activite spécifique de la base de données après l'avoir retrouvé.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // Essayez d'abord de trouver l'activite, y compris ceux qui sont "soft deleted".
        $activite = Activite::withTrashed()->findOrFail($id);

        // Supprimez définitivement l'hebergment.
        $activite->forceDelete();

        return response()->json([
            'message' => 'activite deleted permanently!'
        ], 204);
    }

    /**
     * Archive (soft delete) une activite spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        $activite = Activite::findOrFail($id);

        // Si l'activite n'est pas déjà "soft deleted", on le "soft delete"
        if (!$activite->trashed()) {
            $activite->delete();
            return response()->json([
                'message' => 'activite archived successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'activite is already archived.'
            ], 400);
        }
    }

    /**
     * Restaure une activite qui a été "soft deleted".
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // Récupère les activites "soft deleted" seulement
        $activite = Activite::onlyTrashed()->findOrFail($id);

        // Si l'activite est "soft deleted", on le restaure
        if ($activite->trashed()) {
            $activite->restore();
            return response()->json([
                'message' => 'activite restored successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'activite is not archived.'
            ], 400);
        }
    }

    /**
     * Affiche la liste des activites qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedActivity(): JsonResponse
    {
        // Récupère uniquement les activites qui sont "soft deleted" avec leur relation "lieu"
        $activite = Activite::onlyTrashed()->with('place')->get();
        return response()->json(['data' => $activite]);
    }

    /**
     * Récupère et affiche les détails d'une activite archivé spécifique par son ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function showArchivedActivity(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les activites archivés
        $activite = Activite::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $activite]);
    }
}

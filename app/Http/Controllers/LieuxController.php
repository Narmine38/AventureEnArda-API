<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Lieux;

class LieuxController extends Controller
{
    /**
     * Récupère et renvoie la liste de tous les lieux.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $lieux = Lieux::all();
        return response()->json($lieux, 200);
    }

    /**
     * Valide et stocke un nouveau lieu dans la base de données.
     * Les champs comme 'photo' sont attendus en tant que string. Assurez-vous de les traiter correctement si ce sont des fichiers.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|string', // Si 'photo' est un fichier, modifiez cette validation en conséquence
            'anecdote' => 'nullable|string'
        ]);

        $lieu = Lieux::create($data);

        return response()->json($lieu, 201);
    }

    /**
     * Récupère et renvoie les détails d'un lieu spécifique.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $lieu = Lieux::findOrFail($id);
        return response()->json($lieu, 200);
    }

    /**
     * Valide et met à jour un lieu spécifique dans la base de données.
     *
     * @param Request $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'photo' => 'sometimes|required|string', // Si 'photo' est un fichier, modifiez cette validation en conséquence
            'anecdote' => 'nullable|string'
        ]);

        $lieu = Lieux::findOrFail($id);
        $lieu->update($data);

        return response()->json($lieu, 200);
    }

    /**
     * Supprime un lieu spécifique de la base de données après l'avoir retrouvé.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // Essayez d'abord de trouver le lieu, y compris ceux qui sont "soft deleted".
        $user = Lieux::withTrashed()->findOrFail($id);

        // Supprimez définitivement le lieu.
        $user->forceDelete();

        return response()->json([
            'message' => 'Lieux deleted permanently!'
        ], 204);
    }

    /**
     * Archive (soft delete) un lieu spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        $lieu = Lieux::findOrFail($id);

        // Si le lieu n'est pas déjà "soft deleted", on le "soft delete"
        if (!$lieu->trashed()) {
            $lieu->delete();
            return response()->json([
                'message' => 'Lieu archived successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'Lieu is already archived.'
            ], 400);
        }
    }

    /**
     * Restaure un lieu qui a été "soft deleted".
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // Récupère les lieux "soft deleted" seulement
        $lieu = Lieux::onlyTrashed()->findOrFail($id);

        // Si le lieu est "soft deleted", on le restaure
        if ($lieu->trashed()) {
            $lieu->restore();
            return response()->json([
                'message' => 'Lieu restored successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'Lieu is not archived.'
            ], 400);
        }
    }

    /**
     * Affiche la liste des lieux qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedLieu(): JsonResponse
    {
        // Récupère uniquement les lieux qui sont "soft deleted"
        $lieu = Lieux::onlyTrashed()->get();
        return response()->json(['data' => $lieu]);
    }

    /**
     * Récupère et affiche les détails d'un lieu archivé spécifique par son ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function showArchivedLieu(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les lieux archivés
        $lieu = Lieux::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $lieu]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Lieux;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Hebergement;

class HebergementController extends Controller
{
    /**
     * Récupère et renvoie la liste de tous les hébergements.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $hebergements = Hebergement::with('lieu')->get();
        return response()->json($hebergements, 200);
    }
    /**
     * Valide les données entrantes et crée un nouvel hébergement dans la base de données.
     * Notez que la photo est traitée comme une chaîne; veillez à la traiter correctement si elle représente un fichier.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'photo' => 'required|string', // Si 'photo' est un fichier, modifiez cette validation en conséquence
            'lieu_id' => 'required|integer|exists:lieux,id'
        ]);

        $hebergement = Hebergement::create($data);

        return response()->json($hebergement, 201);
    }

    /**
     * Récupère et renvoie les détails d'un hébergement spécifique.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $hebergement = Hebergement::findOrFail($id);
        return response()->json($hebergement, 200);
    }

    /**
     * Valide les données entrantes et met à jour un hébergement spécifique dans la base de données.
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
            'prix' => 'sometimes|required|numeric',
            'photo' => 'sometimes|required|string', // Si 'photo' est un fichier, modifiez cette validation en conséquence
            'lieu_id' => 'sometimes|required|integer|exists:lieux,id'
        ]);

        $hebergement = Hebergement::findOrFail($id);
        $hebergement->update($data);

        return response()->json($hebergement, 200);
    }

    /**
     * Supprime un hebergment spécifique de la base de données après l'avoir retrouvé.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // Essayez d'abord de trouver l'hebergment, y compris ceux qui sont "soft deleted".
        $hebergement = Hebergement::withTrashed()->findOrFail($id);

        // Supprimez définitivement l'hebergment.
        $hebergement->forceDelete();

        return response()->json([
            'message' => 'Hebergement deleted permanently!'
        ], 204);
    }

    /**
     * Archive (soft delete) un hebergment spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        $hebergement = Hebergement::findOrFail($id);

        // Si l'hebergment n'est pas déjà "soft deleted", on le "soft delete"
        if (!$hebergement->trashed()) {
            $hebergement->delete();
            return response()->json([
                'message' => 'Hebergement archived successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'Hebergement is already archived.'
            ], 400);
        }
    }

    /**
     * Restaure un hebergment qui a été "soft deleted".
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // Récupère les lieux "soft deleted" seulement
        $hebergement = Hebergement::onlyTrashed()->findOrFail($id);

        // Si l'hebergment est "soft deleted", on le restaure
        if ($hebergement->trashed()) {
            $hebergement->restore();
            return response()->json([
                'message' => 'Hebergement restored successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'Hebergement is not archived.'
            ], 400);
        }
    }

    /**
     * Affiche la liste des hebergments qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedHebergement(): JsonResponse
    {
        // Récupère uniquement les hebergments qui sont "soft deleted" avec leur relation "lieu"
        $hebergement = Hebergement::onlyTrashed()->with('lieu')->get();
        return response()->json(['data' => $hebergement]);
    }

    /**
     * Récupère et affiche les détails d'un hebergment archivé spécifique par son ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function showArchivedHebegement(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les hebergment archivés
        $hebergement = Hebergement::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $hebergement]);
    }


}

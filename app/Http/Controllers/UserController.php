<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs actifs (non archivés).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Récupère tous les utilisateurs qui ne sont pas "soft deleted"
        $users = User::all();
        return response()->json(['data' => $users]);
    }

    /**
     * Crée et stocke un nouvel utilisateur dans la base de données.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        // Attribue le rôle 'user' au nouvel utilisateur.
        $user->assignRole('user');

        return response()->json([
            'message' => 'User registered successfully!'
        ], 201);
    }

    /**
     * Récupère et affiche les détails d'un utilisateur spécifique.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user]);
    }

    /**
     * Met à jour les informations d'un utilisateur spécifique.
     *
     * @param Request $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:15',
            'postal_code' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:255',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Récupère les rôles de l'utilisateur
        $roles = $user->getRoleNames();

        return response()->json([
            'data' => $user,
            'message' => 'User updated successfully!',
            'roles' => $roles
        ]);
    }


    /**
     * Supprime définitivement un utilisateur de la base de données.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        // Essayez d'abord de trouver l'utilisateur, y compris ceux qui sont "soft deleted".
        $user = User::withTrashed()->findOrFail($id);

        // Supprimez définitivement l'utilisateur.
        $user->forceDelete();

        return response()->json([
            'message' => 'User deleted permanently!'
        ], 204);
    }

    /**
     * Archive (soft delete) un utilisateur spécifique.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Si l'utilisateur n'est pas déjà "soft deleted", on le "soft delete"
        if (!$user->trashed()) {
            $user->delete();
            return response()->json([
                'message' => 'User archived successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'User is already archived.'
            ], 400);
        }
    }

    /**
     * Restaure un utilisateur qui a été "soft deleted".
     *
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        // Récupère les utilisateurs "soft deleted" seulement
        $user = User::onlyTrashed()->findOrFail($id);

        // Si l'utilisateur est "soft deleted", on le restaure
        if ($user->trashed()) {
            $user->restore();
            return response()->json([
                'message' => 'User restored successfully!'
            ]);
        } else {
            return response()->json([
                'message' => 'User is not archived.'
            ], 400);
        }
    }

    /**
     * Affiche la liste des utilisateurs qui ont été archivés (soft deleted).
     *
     * @return JsonResponse
     */
    public function archivedUsers(): JsonResponse
    {
        // Récupère uniquement les utilisateurs qui sont "soft deleted"
        $users = User::onlyTrashed()->get();
        return response()->json(['data' => $users]);
    }

    /**
     * Récupère et affiche les détails d'un utilisateur archivé spécifique par son ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function showArchivedUser(string $id): JsonResponse
    {
        // Utilisez la méthode onlyTrashed pour récupérer uniquement les utilisateurs archivés
        $user = User::onlyTrashed()->findOrFail($id);
        return response()->json(['data' => $user]);
    }
}

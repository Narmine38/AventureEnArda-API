<?php

namespace App\Http\Controllers;

// Importation des classes nécessaires.
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Méthode de connexion.
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $roles = $user->getRoleNames();  // Cette méthode renverra une collection

        return response([
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => $user,
            'roles' => $roles  // Ajoutez les rôles à la réponse
        ]);
    }


    // Méthode de déconnexion.
    public function logout(Request $request)
    {
        // Vérifie si l'utilisateur est connecté.
        if ($request->user()) {
            // Si l'utilisateur est connecté, supprime le token d'accès actuel.
            $request->user()->currentAccessToken()->delete();
        }

        // Renvoie une réponse de déconnexion réussie.
        return response(['message' => 'Logged out successfully.']);
    }
}

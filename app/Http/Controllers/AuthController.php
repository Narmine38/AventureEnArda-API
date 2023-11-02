<?php

namespace App\Http\Controllers;

// Importation des classes nécessaires.
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        // Créer le cookie
        $cookie = cookie('auth_token', $token);

        $roles = $user->getRoleNames();

        // Renvoyer la réponse avec le cookie

        return response([
            'message' => 'Logged in successfully.',
            'user' => $user,
            'roles' => $roles
        ])->withCookie($cookie);
    }



    // Méthode de déconnexion.
    public function logout(Request $request)
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $request->user();

        // Si aucun utilisateur n'est authentifié, renvoyer une erreur
        if (!$user) {
            Log::info('Logout attempt without authentication.');
            return response(['message' => 'Not logged in.'], 401);
        }

        // Révoquer tous les tokens pour cet utilisateur
        $user->tokens()->delete();

        // Supprimer le cookie
        $cookie = Cookie::forget('auth_token');

        return response(['message' => 'Logged out successfully.'])->withCookie($cookie);
    }

}

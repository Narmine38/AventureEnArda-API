<?php

namespace App\Http\Controllers;

// Importation des classes nécessaires.
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Méthode de connexion.
    public function login(Request $request)
    {
        // Valider les données entrantes
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Vérifier les identifiants de l'utilisateur
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        $user = Auth::user();
        $roles = $user->getRoleNames();

        // Authentification réussie, retourner une réponse réussie
        return response()->json([
            'message' => 'Connexion réussie!',
            'user' => $user,
            'roles' => $roles,
        ], 200);
    }





    // Méthode de déconnexion.
    public function logout(Request $request)
    {
        // Déconnecter l'utilisateur
        Auth::guard('web')->logout();

        // Invalider la session de l'utilisateur
        $request->session()->invalidate();

        // Régénérer le CSRF token (important pour la sécurité)
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Déconnexion réussie!'], 200);
    }
}

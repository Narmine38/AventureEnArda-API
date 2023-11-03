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

    public function login(Request $request)
    {
        // Valider les informations de connexion
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Récupérer l'utilisateur par e-mail
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et que le mot de passe correspond
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Révoquer tous les tokens existants
        $user->tokens()->delete();

        // Créer un nouveau token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public function logout(Request $request)
    {
        // Révoquer le token de l'utilisateur
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully!']);
    }
}

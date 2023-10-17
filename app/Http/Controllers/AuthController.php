<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $cookie = cookie('auth_token', $token, 60, null, null, false, true);

        return response(['message' => 'Logged in successfully.'])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $cookie = Cookie::forget('auth_token');

        return response(['message' => 'Logged out successfully.'])->withCookie($cookie);
    }
}

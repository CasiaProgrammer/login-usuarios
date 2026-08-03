<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'dpi' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('dpi', $validated['dpi'])->first();

        if (!$usuario || !Hash::check($validated['password'], $usuario->password)) {
            throw ValidationException::withMessages([
                'dpi' => ['El DPI o la contraseña son incorrectos.'],
            ]);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token,
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada'], 200);
    }
}
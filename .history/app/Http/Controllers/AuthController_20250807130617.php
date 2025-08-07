<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Valida os dados de entrada
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tenta autenticar o usuário
        if (Auth::attempt($credentials)) {
            // Se autenticado, gera um token de acesso
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            // Retorna o token em formato JSON
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        // Se não autenticado, retorna erro
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

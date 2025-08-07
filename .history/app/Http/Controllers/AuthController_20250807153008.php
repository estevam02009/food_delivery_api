<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validação das credenciais
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Tenta autenticar o usuário
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 3. Gera um token para o usuário
            $token = $user->createToken('authToken')->plainTextToken;

            // 4. Retorna o token e os dados do usuário
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        // 5. Se a autenticação falhar, retorna um erro 401
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    public function register(Request $request)
    {
        // 1. Validação dos dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Criação do usuário
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // 3. Gera um token para o usuário
        $token = $user->createToken('authToken')->plainTextToken;

        // 4. Retorna o token e os dados do usuário
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}

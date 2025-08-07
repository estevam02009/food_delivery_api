<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;


class AddressController extends Controller
{
    public function index()
    {
        // Pegar o usuário autenticado
        $user = Auth::user();
        // Retornar todos os endereços do usuário
        $addresses = $user->addresses;

        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        // Validação de dados
        $validatedData = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'is_primary' => 'boolean',
        ]);

        $user = Auth::user();
        // Criar um novo endereço para o usuário
        $address = $user->addresses()->create($validatedData);

        return response()->json($address, 201);
    }

    public function show($id)
    {
        // Pegar o usuário autenticado
        $user = Auth::user();
        // Buscar o endereço pelo ID
        $address = $user->addresses()->find($id);

        return response()->json($address);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;


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
    }
}

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
        // Retornar os endereços do usuário
        $addresses = $user->addresses;

        return response()->json($addresses);
    }
}

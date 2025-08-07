<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'is_primary' => 'boolean',
        ]);

        $user = Auth::user();
        $address = $user->addresses()->create($validatedData);
        return response()->json($address, 201);
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        return response()->json($address);
    }
}

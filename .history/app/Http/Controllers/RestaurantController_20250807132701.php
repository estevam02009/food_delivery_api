<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return response()->json($restaurants);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([ // Corrigido para $validatedData
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        $restaurant = Restaurant::create($validatedData);
        return response()->json($restaurant, 201);
    }

    public function show(string $id)
    {
        // O findOrFail já lança 404 se não encontrar, então a verificação manual é removida.
        $restaurant = Restaurant::findOrFail($id);
        return response()->json($restaurant);
    }

    public function update(Request $request, string $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validatedData = $request->validate([ // Corrigido para $validatedData
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        $restaurant->update($validatedData);
        return response()->json($restaurant);
    }

    public function destroy(string $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        // Retorna 204 No Content para exclusão bem-sucedida
        return response()->json(null, 204);
    }
}

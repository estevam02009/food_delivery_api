<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        // Pega todos os restaurantes
        $restaurants = Restaurant::all();
        // Retorna os restaurantes em formato JSON
        return response()->json($restaurants);
    }

    public function store(Request $request)
    {
        // Valida os dados de entrada
        $validtedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        // Cria o novo  restaurante no banco de dados
        $restaurant = Restaurant::create($validtedData);

        // Retorna o restaurante criado em formato JSON com status 201 (Created)
        return response()->json($restaurant, 201);
    }

    public function show(string $id)
    {
        // Busca o restaurante pelo ID
        $restaurant = Restaurant::findOrFail($id);

        // Se o restaurante não for encontrado, retorna um erro 404
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        // Retorna o restaurante em formato JSON
        return response()->json($restaurant);
    }

    public function update(Request $request, string $id)
    {
        // Busca o restaurante pelo ID
        $restaurant = Restaurant::findOrFail($id);

        // Valida os dados de entrada
        $validtedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        // Atualiza o restaurante no banco de dados
        $restaurant->update($validtedData);

        // Retorna o restaurante atualizado em formato JSON
        return response()->json($restaurant);
    }

    public function destroy(string $id)
    {
        // Busca o restaurante pelo ID
        $restaurant = Restaurant::findOrFail($id);

        // Deleta o restaurante do banco de dados
        $restaurant->delete();

        // Retorna uma mensagem de sucesso em formato JSON
        return response()->json(['message' => 'Restaurant deleted successfully']);
    }
}

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
}

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
        return response()->json($restaurants);
    }
}

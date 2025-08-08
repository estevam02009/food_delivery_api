<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Restaurant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ReviewCreated;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews for a resource.
     */
    public function index(string $reviewableType, string $reviewableId)
    {
        // Define o modelo correto com base no tipo
        $model = $reviewableType === 'restaurants' ? Restaurant::class : Product::class;

        // Busca o recurso (restaurante ou produto)
        $reviewable = $model::findOrFail($reviewableId);

        // Retorna as avaliações do recurso, carregando o usuário
        $reviews = $reviewable->reviews()->with('user')->get();

        return response()->json($reviews);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, string $reviewableType, string $reviewableId)
    {
        // Define o modelo correto
        $model = $reviewableType === 'restaurants' ? Restaurant::class : Product::class;

        // Busca o recurso
        $reviewable = $model::findOrFail($reviewableId);

        // Validação da avaliação
        $validatedData = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        // Cria a avaliação
        $review = $reviewable->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
        ]);

        // Dispara o evento após a criação
        ReviewCreated::dispatch($review);
        // Retorna a avaliação criada
        return response()->json($review, 201);
    }
}

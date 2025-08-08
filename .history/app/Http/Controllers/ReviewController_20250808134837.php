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

    public function update(Request $request, Review $review)
    {
        // 1. Autoriza a ação usando a ReviewPolicy
        $this->authorize('update', $review);

        // 2. Validação dos dados
        $validatedData = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        // 3. Atualiza a avaliação
        $review->update($validatedData);

        // 4. Dispara o evento para atualizar a nota média
        ReviewCreated::dispatch($review);

        return response()->json($review);
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // 1. Autoriza a ação usando a ReviewPolicy
        $this->authorize('delete', $review);

        // 2. Deleta a avaliação
        $review->delete();

        // 3. Dispara o evento para atualizar a nota média
        ReviewCreated::dispatch($review);

        return response()->noContent();
    }
}

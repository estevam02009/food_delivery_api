<?php

// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        // Pegar o usuário autenticado
        $user = Auth::user();

        // Buscar todos os pedidos do usuário, carregando os itens e produtos
        $orders = $user->orders()->with('orderItems.product')->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        // 1. Validação dos dados de entrada
        $validatedData = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Usamos uma transação para garantir que tudo seja salvo ou nada seja salvo
        DB::beginTransaction();

        try {
            // 2. Busca o usuário autenticado
            $user = Auth::user();
            $totalPrice = 0;

            // 3. Cria o pedido inicial (ainda sem o preço total)
            $order = Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $validatedData['restaurant_id'],
                'delivery_address' => $validatedData['delivery_address'],
                'status' => 'pending',
                'total_price' => 0, // Inicia com 0, o preço será calculado a seguir
            ]);

            // 4. Processa os itens do pedido
            foreach ($validatedData['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Adiciona o preço do item ao total
                $itemPrice = $product->price * $item['quantity'];
                $totalPrice += $itemPrice;

                // 5. Salva cada item do pedido
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            // 6. Atualiza o preço total do pedido
            $order->total_price = $totalPrice;
            $order->save();

            // 7. Confirma a transação no banco de dados
            DB::commit();

            // 8. Retorna o pedido completo com os itens
            $order->load('orderItems.product');
            return response()->json($order, 201);

        } catch (\Exception $e) {
            // Se algo der errado, desfaz tudo
            DB::rollBack();

            // Retorna o erro real, em vez da mensagem genérica
            return response()->json(['message' => 'Ocorreu um erro ao criar o pedido.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        // Pegar o usuário autenticado
        $user = Auth::user();
        // Buscar o pedido pelo ID
        $order = $user->orders()->with('orderItems.product')->findOrFail($id);

        return response()->json($order);
    }

    public function updateStatus(Request $request, string $id)
    {
        // Buscar o pedido pelo ID
        $order = Order::findOrFail($id);

        // Valida o novo status
        $validatedData = $request->validate([
            'status' => 'required|in:pending,preparing,delivery,completed,canceled',
        ]);

        // Atualiza o status do pedido
        $order->update(['status' => $validatedData['status']]);

        // Retorna o pedido atualizado
        return response()->json($order);
    }
}

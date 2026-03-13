<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Muestra el historial de pedidos del usuario.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.book')
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Muestra el detalle de un pedido específico.
     */
    public function show(Order $order)
    {
        // Verificamos propiedad
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.book.author');

        return view('orders.show', compact('order'));
    }

    /**
     * Procesa el carrito actual y lo convierte en un pedido.
     */
    public function store()
    {
        $cart = Cart::where('user_id', Auth::id())->with('cartItems.book')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        try {
            DB::beginTransaction();

            $total = 0;
            foreach ($cart->cartItems as $item) {
                $total += $item->book->price;
            }

            // Crear el Pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'total_amount' => $total,
                'status' => 'Completed', // Lo marcamos como completado directamente para este ejercicio
            ]);

            // Crear los Ítems del Pedido
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'unit_price' => $item->book->price,
                ]);
            }

            // Vaciar el Carrito
            $cart->cartItems()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', '¡Compra realizada con éxito! Ya puedes descargar tus libros.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Hubo un error al procesar tu compra. Por favor, reintenta.');
        }
    }
}

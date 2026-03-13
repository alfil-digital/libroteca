<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Muestra el contenido del carrito del usuario.
     */
    public function index()
    {
        // Obtenemos el carrito del usuario con sus ítems y los detalles de los libros
        $cart = Cart::where('user_id', Auth::id())->with('cartItems.book.author')->first();

        // Calculamos el total
        $total = 0;
        if ($cart) {
            foreach ($cart->cartItems as $item) {
                $total += $item->book->price;
            }
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Añade un libro al carrito.
     */
    public function add(Book $book)
    {
        // Buscamos o creamos el carrito para el usuario actual
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['last_activity' => now()]
        );

        // Verificamos si el libro ya está en el carrito para evitar duplicados (opcional, según lógica de negocio)
        $exists = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $book->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'Este libro ya está en tu carrito.');
        }

        // Creamos el nuevo ítem en el carrito
        CartItem::create([
            'cart_id' => $cart->id,
            'book_id' => $book->id,
        ]);

        // Actualizamos la actividad del carrito
        $cart->update(['last_activity' => now()]);

        return redirect()->route('cart.index')->with('success', 'Libro añadido al carrito correctamente.');
    }

    /**
     * Elimina un ítem específico del carrito.
     */
    public function remove(CartItem $cartItem)
    {
        // Verificamos que el ítem pertenezca al carrito del usuario autenticado
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Libro eliminado del carrito.');
    }

    /**
     * Vacía todo el carrito del usuario.
     */
    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return redirect()->back()->with('success', 'Carrito vaciado correctamente.');
    }
}

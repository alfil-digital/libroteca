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
     * Muestra el contenido del carrito (de BD si está autenticado, de sesión si es invitado).
     */
    public function index()
    {
        $items = [];
        $total = 0;

        if (Auth::check()) {
            // Lógica para usuario autenticado (Base de Datos)
            $cart = Cart::where('user_id', Auth::id())->with('cartItems.sellable.author')->first();
            if ($cart) {
                foreach ($cart->cartItems as $dbItem) {
                    if ($dbItem->sellable) {
                        $items[] = (object)[
                            'id' => $dbItem->id, // ID del CartItem para borrar
                            'sellable_id' => $dbItem->sellable_id,
                            'sellable_type' => $dbItem->sellable_type,
                            'sellable' => $dbItem->sellable,
                        ];
                        $total += $dbItem->sellable->price;
                    }
                }
            }
        } else {
            // Lógica para invitado (Sesión)
            $sessionCart = session()->get('cart', []);
            foreach ($sessionCart as $index => $item) {
                $model = $item['sellable_type']::find($item['sellable_id']);
                if ($model) {
                    $items[] = (object)[
                        'id' => $index, // Usamos el índice del array para borrar
                        'sellable_id' => $item['sellable_id'],
                        'sellable_type' => $item['sellable_type'],
                        'sellable' => $model,
                    ];
                    $total += $model->price;
                }
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Añade un producto al carrito.
     */
    public function add(Request $request)
    {
        $request->validate([
            'sellable_id' => 'required|integer',
            'sellable_type' => 'required|string',
        ]);

        if (Auth::check()) {
            // Lógica BD
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()], ['last_activity' => now()]);
            $exists = CartItem::where('cart_id', $cart->id)
                ->where('sellable_id', $request->sellable_id)
                ->where('sellable_type', $request->sellable_type)
                ->exists();

            if ($exists) {
                return redirect()->back()->with('info', 'Este producto ya está en tu carrito.');
            }

            CartItem::create([
                'cart_id' => $cart->id,
                'sellable_id' => $request->sellable_id,
                'sellable_type' => $request->sellable_type,
            ]);
            $cart->update(['last_activity' => now()]);
        } else {
            // Lógica Sesión
            $cart = session()->get('cart', []);
            
            // Verificar si ya existe
            foreach ($cart as $item) {
                if ($item['sellable_id'] == $request->sellable_id && $item['sellable_type'] == $request->sellable_type) {
                    return redirect()->back()->with('info', 'Este producto ya está en tu carrito.');
                }
            }

            $cart[] = [
                'sellable_id' => $request->sellable_id,
                'sellable_type' => $request->sellable_type,
            ];

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto añadido al carrito correctamente.');
    }

    /**
     * Elimina un ítem específico del carrito.
     */
    public function remove($id, Request $request) // Cambio de TypeHinting para aceptar ID de sesión o BD
    {
        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($id);
            if ($cartItem->cart->user_id !== Auth::id()) {
                abort(403);
            }
            $cartItem->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vacía todo el carrito.
     */
    public function clear()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cart->cartItems()->delete();
            }
        } else {
            session()->forget('cart');
        }

        return redirect()->back()->with('success', 'Carrito vaciado correctamente.');
    }
}

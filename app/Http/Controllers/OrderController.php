<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MercadoPagoService;
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
            ->with('orderItems.sellable')
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

        $order->load('orderItems.sellable');

        return view('orders.show', compact('order'));
    }

    /**
     * Procesa el carrito actual y crea un pedido con pago pendiente.
     */
    public function store(Request $request, MercadoPagoService $mercadoPagoService)
    {
        $request->validate([
            'payment_method' => 'nullable|string|in:all,credit_card,rapipago',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('cartItems.sellable')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $paymentMethod = $request->input('payment_method', 'all');

        try {
            DB::beginTransaction();

            $total = 0;
            foreach ($cart->cartItems as $item) {
                if ($item->sellable) {
                    $total += $item->sellable->price;
                }
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'total_amount' => $total,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'external_reference' => 'ORDER-' . time() . '-' . Auth::id(),
                'payment_method' => $paymentMethod,
            ]);

            foreach ($cart->cartItems as $item) {
                if ($item->sellable) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'sellable_id' => $item->sellable_id,
                        'sellable_type' => $item->sellable_type,
                        'unit_price' => $item->sellable->price,
                    ]);
                }
            }

            DB::commit();

            $items = $order->getItemsForMercadoPago();
            $payer = [
                'name' => Auth::user()->name,
                'surname' => Auth::user()->surname ?? '',
                'email' => Auth::user()->email,
            ];

            $preference = $mercadoPagoService->createPreference(
                $items,
                $payer,
                $order->external_reference,
                $paymentMethod
            );

            if ($preference) {
                
                \Log::info('MercadoPago preferencia generada', ['order_id' => $order->id, 'preference' => $preference]);
                $cart->cartItems()->delete();
                return redirect()->away($preference['init_point']);
            }

            $order->updatePaymentStatus(Order::PAYMENT_CANCELLED);
            return redirect()->route('cart.index')->with('error', 'Error al procesar el pago. Por favor, verifica tu configuración de Mercado Pago e intenta de nuevo.');


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Hubo un error al procesar tu compra. Por favor, reintenta.');
        }
    }

    /**
     * Reintentar pago para un pedido existente.
     */
    public function retryPayment(Order $order, MercadoPagoService $mercadoPagoService)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order)->with('info', 'El pago de este pedido ya está completado.');
        }

        $order->external_reference = 'ORDER-' . $order->id . '-' . time();
        $order->status = Order::STATUS_PENDING;
        $order->payment_status = Order::PAYMENT_PENDING;
        $order->save();

        $items = $order->getItemsForMercadoPago();
        $payer = [
            'name' => Auth::user()->name,
            'surname' => Auth::user()->surname ?? '',
            'email' => Auth::user()->email,
        ];

        $preference = $mercadoPagoService->createPreference(
            $items,
            $payer,
            $order->external_reference,
            $order->payment_method ?? 'all'
        );

        if ($preference) {
            \Log::info('MercadoPago preferencia regenerada', ['order_id' => $order->id, 'preference' => $preference]);
            return redirect()->away($preference['init_point']);
        }

        return redirect()->route('orders.show', $order)->with('error', 'No se pudo volver a generar el pago. Intenta más tarde.');
    }

}

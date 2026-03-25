<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller
{
    protected MercadoPagoService $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    /**
     * Mostrar formulario de checkout
     */
    public function checkout(): View
    {
        return view('mercadopago.checkout');
    }

    /**
     * Crear preferencia de pago
     */
    public function createPreference(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.title' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payer.name' => 'nullable|string',
            'payer.surname' => 'nullable|string',
            'payer.email' => 'nullable|email',
            'external_reference' => 'nullable|string',
        ]);

        $preference = $this->mercadoPagoService->createPreference(
            $request->input('items'),
            $request->input('payer', []),
            $request->input('external_reference', '')
        );

        if ($preference) {
            return response()->json([
                'success' => true,
                'preference' => $preference,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la preferencia de pago',
        ], 500);
    }

    /**
     * Página de éxito del pago
     */
    public function success(Request $request): View|RedirectResponse
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        // Aquí puedes procesar el pago exitoso
        Log::info('Pago exitoso', [
            'payment_id' => $paymentId,
            'status' => $status,
            'external_reference' => $externalReference,
        ]);

        // Obtener detalles del pago si es necesario
        $payment = null;
        if ($paymentId) {
            $payment = $this->mercadoPagoService->getPayment($paymentId);
        }

        return view('mercadopago.success', compact('payment', 'status', 'externalReference'));
    }

    /**
     * Página de fallo del pago
     */
    public function failure(Request $request): View
    {
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        Log::warning('Pago fallido', [
            'status' => $status,
            'external_reference' => $externalReference,
        ]);

        return view('mercadopago.failure', compact('status', 'externalReference'));
    }

    /**
     * Página de pago pendiente
     */
    public function pending(Request $request): View
    {
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        Log::info('Pago pendiente', [
            'status' => $status,
            'external_reference' => $externalReference,
        ]);

        return view('mercadopago.pending', compact('status', 'externalReference'));
    }

    /**
     * Webhook para recibir notificaciones de Mercado Pago
     */
    public function webhook(Request $request): JsonResponse
    {
        $data = $request->all();

        Log::info('Webhook recibido de Mercado Pago', $data);

        $processed = $this->mercadoPagoService->processWebhook($data);

        if ($processed) {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     * Obtener estado de un pago
     */
    public function getPaymentStatus(string $paymentId): JsonResponse
    {
        $payment = $this->mercadoPagoService->getPayment($paymentId);

        if ($payment) {
            return response()->json([
                'success' => true,
                'payment' => $payment,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pago no encontrado',
        ], 404);
    }
}
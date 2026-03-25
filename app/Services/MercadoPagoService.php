<?php

namespace App\Services;

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    public function __construct()
    {
        // Configurar SDK con el access token
        SDK::setAccessToken(config('mercadopago.access_token'));
    }

    /**
     * Crear una preferencia de pago
     *
     * @param array $items Array de items a pagar
     * @param array $payer Información del pagador
     * @param string $externalReference Referencia externa (ID del pedido)
     * @return array|null
     */
    public function createPreference(array $items, array $payer = [], string $externalReference = ''): ?array
    {
        try {
            $preference = new Preference();

            // Agregar items
            $preference->items = $this->createItems($items);

            // Configurar pagador si se proporciona
            if (!empty($payer)) {
                $preference->payer = $this->createPayer($payer);
            }

            // URLs de retorno
            $preference->back_urls = [
                "success" => url(config('mercadopago.success_url')),
                "failure" => url(config('mercadopago.failure_url')),
                "pending" => url(config('mercadopago.pending_url')),
            ];

            $preference->auto_return = "approved";

            // Referencia externa
            if ($externalReference) {
                $preference->external_reference = $externalReference;
            }

            // URL de webhook
            $preference->notification_url = url(config('mercadopago.webhook_url'));

            $preference->save();

            return [
                'id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ];

        } catch (\Exception $e) {
            Log::error('Error creando preferencia de Mercado Pago: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear items para la preferencia
     */
    private function createItems(array $items): array
    {
        $mpItems = [];

        foreach ($items as $item) {
            $mpItem = new Item();
            $mpItem->title = $item['title'];
            $mpItem->quantity = $item['quantity'] ?? 1;
            $mpItem->unit_price = $item['unit_price'];
            $mpItem->currency_id = config('mercadopago.currency');

            if (isset($item['description'])) {
                $mpItem->description = $item['description'];
            }

            if (isset($item['picture_url'])) {
                $mpItem->picture_url = $item['picture_url'];
            }

            $mpItems[] = $mpItem;
        }

        return $mpItems;
    }

    /**
     * Crear objeto pagador
     */
    private function createPayer(array $payerData): Payer
    {
        $payer = new Payer();
        $payer->name = $payerData['name'] ?? '';
        $payer->surname = $payerData['surname'] ?? '';
        $payer->email = $payerData['email'] ?? '';

        if (isset($payerData['identification'])) {
            $payer->identification = $payerData['identification'];
        }

        return $payer;
    }

    /**
     * Obtener información de un pago por ID
     */
    public function getPayment(string $paymentId): ?array
    {
        try {
            $payment = \MercadoPago\Payment::find_by_id($paymentId);

            return [
                'id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'amount' => $payment->transaction_amount,
                'currency' => $payment->currency_id,
                'date_created' => $payment->date_created,
                'date_approved' => $payment->date_approved,
                'external_reference' => $payment->external_reference,
                'payment_method_id' => $payment->payment_method_id,
                'payment_type_id' => $payment->payment_type_id,
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo pago de Mercado Pago: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Procesar webhook de Mercado Pago
     */
    public function processWebhook(array $data): bool
    {
        try {
            $topic = $data['topic'] ?? '';
            $id = $data['id'] ?? '';

            if ($topic === 'payment') {
                $payment = $this->getPayment($id);

                if ($payment) {
                    // Aquí puedes procesar el pago según su estado
                    Log::info('Pago procesado vía webhook', $payment);

                    // Ejemplo: actualizar estado del pedido en la base de datos
                    // $this->updateOrderStatus($payment['external_reference'], $payment['status']);

                    return true;
                }
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Error procesando webhook de Mercado Pago: ' . $e->getMessage());
            return false;
        }
    }
}
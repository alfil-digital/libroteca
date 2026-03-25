<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Order;

class MercadoPagoService
{
    private bool $enabled = false;

    public function __construct()
    {
        try {
            $token = config('mercadopago.access_token');
            if (empty($token)) {
                $this->enabled = false;
                Log::warning('MercadoPago access_token no encontrado en config. Revisa tu .env.');
                return;
            }

            $this->enabled = class_exists('MercadoPago\MercadoPagoConfig');
            if ($this->enabled) {
                \MercadoPago\MercadoPagoConfig::setAccessToken($token);
                $env = config('mercadopago.environment', 'sandbox');
                $runtime = $env === 'sandbox' ? \MercadoPago\MercadoPagoConfig::LOCAL : \MercadoPago\MercadoPagoConfig::SERVER;
                \MercadoPago\MercadoPagoConfig::setRuntimeEnviroment($runtime);
            } else {
                Log::warning('MercadoPago SDK no disponible: clase MercadoPagoConfig no encontrada.');
            }
        } catch (\TypeError $e) {
            $this->enabled = false;
            Log::error('Tipo inválido en MercadoPago access token: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->enabled = false;
            Log::warning('MercadoPago SDK no configurado: ' . $e->getMessage());
        }
    }

    /**
     * Crear una preferencia de pago
     */
    public function createPreference(array $items, array $payer = [], string $externalReference = '', string $selectedPaymentMethod = 'all'): ?array
    {
        if (!$this->enabled) {
            Log::error('Intento de crear preferencia sin Mercado Pago configurado');
            return null;
        }

        try {
            $payloadItems = [];
            foreach ($items as $item) {
                $payloadItems[] = [
                    'title' => $item['title'],
                    'quantity' => intval($item['quantity'] ?? 1),
                    'unit_price' => floatval($item['unit_price']),
                    'currency_id' => config('mercadopago.currency', 'ARS'),
                ];
            }

            $successUrl = url(config('mercadopago.success_url'));
            $failureUrl = url(config('mercadopago.failure_url'));
            $pendingUrl = url(config('mercadopago.pending_url'));

            $preferencePayload = [
                'items' => $payloadItems,
                'back_urls' => [
                    'success' => $successUrl,
                    'failure' => $failureUrl,
                    'pending' => $pendingUrl,
                ],
                'external_reference' => $externalReference,
                'notification_url' => url(config('mercadopago.webhook_url')),
            ];

            // Mercado Pago sandbox puede rechazar auto_return con localhost.
            if (!str_contains($successUrl, 'localhost')) {
                $preferencePayload['auto_return'] = 'approved';
            } else {
                \Log::info('MercadoPago auto_return omitido para localhost', ['success_url' => $successUrl]);
            }

            if (!empty($payer)) {
                $preferencePayload['payer'] = [
                    'name' => $payer['name'] ?? '',
                    'surname' => $payer['surname'] ?? '',
                    'email' => $payer['email'] ?? '',
                ];
            }

            if ($selectedPaymentMethod === 'credit_card') {
                $preferencePayload['payment_methods'] = [
                    'excluded_payment_types' => [
                        ['id' => 'ticket'],
                        ['id' => 'atm'],
                        ['id' => 'bank_transfer'],
                        ['id' => 'digital_wallet'],
                        ['id' => 'debit_card'],
                        ['id' => 'prepaid_card'],
                    ],
                    'installments' => 12,
                ];
            } elseif ($selectedPaymentMethod === 'rapipago') {
                $preferencePayload['payment_methods'] = [
                    'excluded_payment_types' => [
                        ['id' => 'credit_card'],
                        ['id' => 'debit_card'],
                        ['id' => 'atm'],
                        ['id' => 'bank_transfer'],
                        ['id' => 'digital_wallet'],
                        ['id' => 'prepaid_card'],
                    ],
                    'installments' => 1,
                    'default_payment_method_id' => 'rapipago',
                ];
            }

            $client = new \MercadoPago\Client\Preference\PreferenceClient();
            $preference = $client->create($preferencePayload);

            return [
                'id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ];

        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            Log::error('Error creando preferencia MP: Api error', [
                'status' => $apiResponse->getStatusCode(),
                'body' => $apiResponse->getContent(),
                'request_payload' => $preferencePayload,
                'exception_message' => $e->getMessage(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error creando preferencia MP: ' . $e->getMessage(), ['exception' => $e, 'request_payload' => $preferencePayload]);
            return null;
        }
    }

    /**
     * Obtener información de un pago por ID
     */
    public function getPayment(string $paymentId): ?array
    {
        if (!$this->enabled) {
            Log::error('Intento de consultar pago sin Mercado Pago configurado');
            return null;
        }

        try {
            $client = new \MercadoPago\Client\Payment\PaymentClient();
            $payment = $client->get(intval($paymentId));

            return [
                'id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail ?? '',
                'amount' => $payment->transaction_amount,
                'currency' => $payment->currency_id,
                'date_created' => $payment->date_created,
                'date_approved' => $payment->date_approved,
                'external_reference' => $payment->external_reference,
                'payment_method_id' => $payment->payment_method_id,
                'payment_type_id' => $payment->payment_type_id,
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo pago MP: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Procesar webhook de Mercado Pago
     */
    public function processWebhook(array $data): bool
    {
        if (!$this->enabled) {
            Log::error('Intento de procesar webhook sin Mercado Pago configurado');
            return false;
        }

        try {
            $topic = $data['topic'] ?? '';
            $id = $data['id'] ?? '';

            if ($topic === 'payment') {
                $payment = $this->getPayment($id);

                if ($payment) {
                    $order = Order::where('external_reference', $payment['external_reference'])->first();

                    if ($order) {
                        $order->payment_method = $payment['payment_method_id'] ?? $order->payment_method;
                        $order->updatePaymentStatus($payment['status'], $payment['id']);

                        Log::info('Pedido actualizado vía webhook', [
                            'order_id' => $order->id,
                            'payment_status' => $payment['status'],
                            'payment_id' => $payment['id'],
                            'payment_method' => $order->payment_method
                        ]);

                        return true;
                    } else {
                        Log::warning('Pedido no encontrado para external_reference', [
                            'external_reference' => $payment['external_reference']
                        ]);
                    }
                }
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Error procesando webhook MP: ' . $e->getMessage());
            return false;
        }
    }
}

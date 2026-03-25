<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para la integración con Mercado Pago.
    | Las credenciales se obtienen de las variables de entorno.
    |
    */

    'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
    'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Ambiente de Mercado Pago: 'sandbox' para pruebas, 'production' para producción
    |
    */
    'environment' => env('MERCADO_PAGO_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Moneda por defecto para las transacciones
    |
    */
    'currency' => env('MERCADO_PAGO_CURRENCY', 'ARS'),

    /*
    |--------------------------------------------------------------------------
    | Success, Failure and Pending URLs
    |--------------------------------------------------------------------------
    |
    | URLs de retorno después del pago
    |
    */
    'success_url' => env('MERCADO_PAGO_SUCCESS_URL', '/payment/success'),
    'failure_url' => env('MERCADO_PAGO_FAILURE_URL', '/payment/failure'),
    'pending_url' => env('MERCADO_PAGO_PENDING_URL', '/payment/pending'),

    /*
    |--------------------------------------------------------------------------
    | Webhook URL
    |--------------------------------------------------------------------------
    |
    | URL para recibir notificaciones de Mercado Pago
    |
    */
    'webhook_url' => env('MERCADO_PAGO_WEBHOOK_URL', '/api/webhooks/mercadopago'),
];
<?php

use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mercado Pago Routes
|--------------------------------------------------------------------------
|
| Rutas para la integración con Mercado Pago
|
*/

// Rutas web (vistas)
Route::get('/checkout', [MercadoPagoController::class, 'checkout'])->name('mercadopago.checkout');
Route::get('/payment/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
Route::get('/payment/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');
Route::get('/payment/pending', [MercadoPagoController::class, 'pending'])->name('mercadopago.pending');

// API routes
Route::prefix('api')->group(function () {
    Route::post('/mercadopago/preference', [MercadoPagoController::class, 'createPreference']);
    Route::get('/mercadopago/payment/{paymentId}', [MercadoPagoController::class, 'getPaymentStatus']);
    Route::post('/webhooks/mercadopago', [MercadoPagoController::class, 'webhook']);
});
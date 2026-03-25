<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Mercado Pago SDK -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Formulario de compra -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Información de Compra</h2>

                            <form id="checkout-form" class="space-y-4">
                                @csrf

                                <!-- Items del carrito (ejemplo) -->
                                <div id="items-container">
                                    <div class="item-row border-b pb-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Producto</label>
                                                <input type="text" name="items[0][title]" value="Libro de Ejemplo"
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                                <input type="number" name="items[0][quantity]" value="1" min="1"
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                                                <input type="number" name="items[0][unit_price]" value="100.00" step="0.01" min="0"
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-item" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    + Agregar otro item
                                </button>

                                <!-- Información del pagador -->
                                <div class="border-t pt-4">
                                    <h3 class="text-md font-medium text-gray-900 mb-3">Información del Comprador</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                            <input type="text" name="payer[name]" value="{{ auth()->user()->name ?? '' }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Apellido</label>
                                            <input type="text" name="payer[surname]" value=""
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="payer[email]" value="{{ auth()->user()->email ?? '' }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>

                                <!-- Referencia externa -->
                                <div class="border-t pt-4">
                                    <label class="block text-sm font-medium text-gray-700">Referencia del Pedido</label>
                                    <input type="text" name="external_reference" value="ORDER-{{ time() }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Método de pago -->
                                <div class="border-t pt-4">
                                    <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                    <select name="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="all">Todos los métodos</option>
                                        <option value="credit_card">Tarjeta de Crédito</option>
                                        <option value="rapipago">Rapipago/Pago en Efectivo</option>
                                    </select>
                                </div>

                                <!-- Botón de pago -->
                                <div class="pt-4">
                                    <button type="submit" id="checkout-btn"
                                            class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                        <span id="btn-text">Pagar con Mercado Pago</span>
                                        <span id="btn-loading" class="hidden ml-2">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Resumen del pago -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Resumen del Pago</h2>

                            <div id="payment-summary" class="space-y-2">
                                <p class="text-sm text-gray-600">Complete el formulario para ver el resumen</p>
                            </div>

                            <!-- Información de Mercado Pago -->
                            <div class="mt-6 pt-6 border-t">
                                <div class="flex items-center">
                                    <img src="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.19.1/mercadopago/logo__large.png"
                                         alt="Mercado Pago" class="h-8 w-auto">
                                    <span class="ml-2 text-sm text-gray-600">Pago seguro con Mercado Pago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mp = new MercadoPago('{{ config("mercadopago.public_key") }}');
            const form = document.getElementById('checkout-form');
            const checkoutBtn = document.getElementById('checkout-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            let itemCount = 1;

            // Agregar más items
            document.getElementById('add-item').addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const newItem = document.createElement('div');
                newItem.className = 'item-row border-b pb-4 mb-4';
                newItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Producto</label>
                            <input type="text" name="items[${itemCount}][title]" value=""
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                            <input type="number" name="items[${itemCount}][quantity]" value="1" min="1"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                            <input type="number" name="items[${itemCount}][unit_price]" value="" step="0.01" min="0"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                `;
                container.appendChild(newItem);
                itemCount++;
            });

            // Procesar el formulario
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Mostrar loading
                checkoutBtn.disabled = true;
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');

                try {
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData);

                    // Convertir items a array
                    const items = [];
                    for (let i = 0; data[`items[${i}][title]`]; i++) {
                        items.push({
                            title: data[`items[${i}][title]`],
                            quantity: parseInt(data[`items[${i}][quantity]`], 10),
                            unit_price: parseFloat(data[`items[${i}][unit_price]`])
                        });
                    }

                    // Crear preferencia
                    const response = await fetch('/api/mercadopago/preference', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            items: items,
                            payer: {
                                name: data['payer[name]'],
                                surname: data['payer[surname]'],
                                email: data['payer[email]']
                            },
                            external_reference: data.external_reference,
                            payment_method: data.payment_method || 'all'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Redirigir a Mercado Pago
                        window.location.href = result.preference.init_point;
                    } else {
                        alert('Error al procesar el pago: ' + result.message);
                    }

                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al procesar el pago');
                } finally {
                    // Ocultar loading
                    checkoutBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
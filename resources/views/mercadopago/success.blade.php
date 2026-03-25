<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago Exitoso - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
            <div class="text-center">
                <!-- Icono de éxito -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="mt-6 text-2xl font-bold text-gray-900">¡Pago Exitoso!</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Tu pago ha sido procesado correctamente.
                </p>

                @if($order)
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Detalles del Pedido</h3>
                    <dl class="space-y-1">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Número de Pedido:</dt>
                            <dd class="text-sm font-medium text-gray-900">#{{ $order->id }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Fecha:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->order_date->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Total:</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Estado:</dt>
                            <dd class="text-sm font-medium text-green-600">{{ $order->status }}</dd>
                        </div>
                    </dl>
                </div>

                @if($order->orderItems->count() > 0)
                <div class="mt-6 bg-white rounded-lg border p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Productos Comprados</h3>
                    <div class="space-y-2">
                        @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $item->sellable->title ?? $item->sellable->name ?? 'Producto' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ $item->sellable_type === 'App\\Models\\Book' ? 'Libro' : 'Curso' }}
                                </p>
                            </div>
                            <span class="text-sm font-medium text-gray-900">
                                ${{ number_format($item->unit_price, 2) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif

                @if($payment)
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Detalles del Pago</h3>
                    <dl class="space-y-1">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">ID del Pago:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $payment['id'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Estado:</dt>
                            <dd class="text-sm font-medium text-green-600">{{ ucfirst($payment['status']) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Monto:</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($payment['amount'], 2) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Método:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $payment['payment_method_id'] ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
                @endif

                <div class="mt-8 space-y-3">
                    <a href="{{ route('dashboard') }}"
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Volver al Inicio
                    </a>

                    <a href="{{ route('orders.index') }}"
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Ver Mis Compras
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
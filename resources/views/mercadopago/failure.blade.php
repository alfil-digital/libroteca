<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago Fallido - {{ config('app.name', 'Laravel') }}</title>

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
                <!-- Icono de error -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h2 class="mt-6 text-2xl font-bold text-gray-900">Pago No Procesado</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Hubo un problema al procesar tu pago.
                </p>

                @if($status)
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Información del Error</h3>
                    <dl class="space-y-1">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Estado:</dt>
                            <dd class="text-sm font-medium text-red-600">{{ ucfirst($status) }}</dd>
                        </div>
                        @if($externalReference)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Referencia:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $externalReference }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endif

                <div class="mt-8 space-y-3">
                    <a href="{{ route('mercadopago.checkout') }}"
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Intentar de Nuevo
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
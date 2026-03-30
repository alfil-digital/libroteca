<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Recuperar Contraseña</h3>
                <p class="text-muted small">
                    ¿Olvidaste tu contraseña? No hay problema. Indícanos tu correo y te enviaremos un enlace para
                    restablecerla.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="d-grid gap-2 mt-4">
                    <x-primary-button>
                        {{ __('Enviar Enlace de Restablecimiento') }}
                    </x-primary-button>
                </div>

                <div class="mt-4 text-center">
                    <a class="text-decoration-none small text-secondary" href="{{ route('login') }}">
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
    </x-app-layout>
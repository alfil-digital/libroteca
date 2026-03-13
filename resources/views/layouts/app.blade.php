<!DOCTYPE html> <!-- Define el tipo de documento como HTML5 -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <!-- Establece el idioma de la página -->

<head>
    <meta charset="utf-8"> <!-- Codificación de caracteres estándar -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Asegura que la web sea responsiva -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Token de seguridad para peticiones AJAX -->

    <title>{{ config('app.name', 'Libroteca') }}</title> <!-- Título de la pestaña del navegador -->

    <!-- Fuentes - Bunny Fonts es una alternativa económica y rápida a Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons CDN (Carga segura y rápida) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Scripts y Estilos procesados por Vite -->
    @vite(['resources/css/app.scss', 'resources/js/app.js']) <!-- Importa Bootstrap mediante Sass y el JS principal -->
</head>

<body class="bg-light"> <!-- Fondo claro de Bootstrap para toda la página -->
    <div class="min-vh-100"> <!-- Altura mínima de pantalla completa -->
        @include('layouts.navigation') <!-- Incluye la barra de navegación (ahora con Bootstrap) -->

        <!-- Encabezado de la Página (Opcional según la vista) -->
        @if (isset($header))
            <header class="bg-white border-bottom shadow-sm"> <!-- Fondo blanco, borde inferior y sombra suave -->
                <div class="container py-4"> <!-- Contenedor centrado con margen vertical -->
                    {{ $header }} <!-- Muestra el slot de encabezado si está definido -->
                </div>
            </header>
        @endif

        <!-- Contenido Principal -->
        <main class="container py-5"> <!-- Contenedor principal con margen vertical generoso -->
            {{ $slot }} <!-- Aquí se inyecta el contenido específico de cada vista -->
        </main>
    </div>
</body>

</html>
<x-app-layout> <!-- Usa el diseño base -->
    <x-slot name="header"> <!-- Título para el encabezado de navegación -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Modificar Usuario') }} <!-- Título de la sección de edición -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta de Bootstrap con sombra leve -->
        <div class="card-header bg-white py-3"> <!-- Cabecera de la sección -->
            <h5 class="card-title mb-0">Información del Perfil: {{ $user->name }}</h5>
            <!-- Muestra el nombre del usuario cargado -->
        </div>
        <div class="card-body p-4"> <!-- Contenido principal del formulario -->
            @include('users.form') <!-- Reutiliza el componente de formulario con los datos del usuario -->
        </div>
    </div>
</x-app-layout>
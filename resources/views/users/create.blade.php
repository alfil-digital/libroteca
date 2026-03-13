<x-app-layout> <!-- Usa el diseño base -->
    <x-slot name="header"> <!-- Título para el encabezado de navegación -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Registrar Nuevo Usuario') }} <!-- Nombre de la acción en español -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta de Bootstrap con sombra y sin bordes -->
        <div class="card-header bg-white py-3"> <!-- Cabecera blanca con padding -->
            <h5 class="card-title mb-0">Datos del Usuario</h5> <!-- Subtítulo del formulario -->
        </div>
        <div class="card-body p-4"> <!-- Cuerpo de la tarjeta con padding generoso -->
            @include('users.form') <!-- Inserta el formulario compartido centrado en la tarjeta -->
        </div>
    </div>
</x-app-layout>
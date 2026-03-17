<x-admin-layout> <!-- Estructura común -->
    <x-slot name="header"> <!-- Encabezado con título -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Editar Categoría') }} <!-- Título dinámico -->
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4"> <!-- Contenedor centrado -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0"> <!-- Tarjeta con sombras suaves -->
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Modificando: {{ $category->name }}</h5>
                    <!-- Muestra el nombre actual -->
                </div>
                <div class="card-body p-4">
                    @include('categories.form') <!-- Carga el formulario con los datos cargados -->
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
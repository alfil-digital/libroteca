<x-admin-layout> <!-- Perímetro de diseño de la aplicación -->
    <x-slot name="header"> <!-- Sección de título -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Editar Rol Existente') }} <!-- Título de edición -->
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4"> <!-- Contenedor centrado -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0"> <!-- Tarjeta sin bordes pronunciados -->
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Modificando: {{ $role->name }}</h5> <!-- Muestra el rol actual -->
                </div>
                <div class="card-body p-4">
                    @include('roles.form') <!-- Fragmento de formulario cargado con datos -->
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
<x-app-layout> <!-- Layout principal -->
    <x-slot name="header"> <!-- Título de la página -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Nueva Categoría') }} <!-- Título en español -->
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4"> <!-- Centra el formulario -->
        <div class="col-md-5"> <!-- Ancho limitado para formularios de un solo campo -->
            <div class="card shadow-sm border-0"> <!-- Tarjeta estética -->
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Crear Género Literario</h5> <!-- Subtítulo -->
                </div>
                <div class="card-body p-4">
                    @include('categories.form') <!-- Incluye el fragmento del formulario -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
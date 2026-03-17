<x-admin-layout> <!-- Estructura común de la aplicación -->
    <x-slot name="header"> <!-- Encabezado de la página -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Nuevo Libro') }} <!-- Título en español -->
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4"> <!-- Centra el contenido en pantalla -->
        <div class="col-md-6"> <!-- Ancho limitado para formularios simples -->
            <div class="card shadow-sm border-0"> <!-- Tarjeta con diseño moderno -->
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Nuevo Libro</h5> <!-- Subtítulo -->
                </div>
                <div class="card-body p-4">
                    @include('books.form') <!-- Incluye el fragmento del formulario -->
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
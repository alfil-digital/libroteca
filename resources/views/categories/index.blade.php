<x-admin-layout> <!-- Estructura común de la aplicación -->
    <x-slot name="header"> <!-- Define el contenido para el encabezado -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Gestión de Categorías / Géneros') }} <!-- Título principal -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta con diseño moderno -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Listado de Categorías</h5> <!-- Subtítulo -->
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Nueva Categoría') }} <!-- Botón de creación -->
            </a>
        </div>

        <div class="card-body p-0"> <!-- Cuerpo de la tarjeta sin relleno para la tabla -->
            <!-- Alertas de éxito o error -->
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-0 mb-0">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-0 mb-0">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive"> <!-- Tabla responsiva -->
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"> <!-- Títulos de columna -->
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nombre de la Categoría</th>
                            <th class="text-end pe-4">Acciones</th> <!-- Botones de gestión -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category) <!-- Itera sobre las categorías -->
                            <tr>
                                <td class="ps-4">{{ $category->id }}</td>
                                <td class="fw-medium text-dark">{{ $category->name }}</td> <!-- Nombre resaltado -->
                                <td class="text-end pe-4"> <!-- Acciones: Editar y Eliminar -->
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="btn btn-outline-secondary btn-sm me-2">
                                        Editar
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de eliminar esta categoría?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación de Bootstrap 5 -->
        @if($categories->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
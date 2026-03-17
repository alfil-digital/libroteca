<x-admin-layout> <!-- Utiliza el componente lateral de diseño de la aplicación -->
    <x-slot name="header"> <!-- Define el contenido para la sección de cabecera -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Gestión de Autores e Instructores') }} <!-- Título principal de la página -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta con sombra suave y sin bordes -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <!-- Cabecera de tarjeta flexible -->
            <h5 class="card-title mb-0">Listado de Autores</h5> <!-- Título del listado -->
            <a href="{{ route('authors.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                <!-- Botón primario redondeado -->
                <i class="bi bi-person-plus-fill me-1"></i> {{ __('Agregar Nuevo Autor') }} <!-- Texto del botón -->
            </a>
        </div>

        <div class="card-body p-0"> <!-- Cuerpo de la tarjeta sin padding para que la tabla sea a sangre -->
            @if(session('success')) <!-- Muestra notificaciones de éxito si las hay -->
                <div class="alert alert-success border-0 rounded-0 mb-0">
                    {{ session('success') }} <!-- Mensaje de confirmación de acción exitosa -->
                </div>
            @endif
            
            @if(session('error')) <!-- Muestra notificaciones de error -->
                <div class="alert alert-danger border-0 rounded-0 mb-0">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive"> <!-- Envuelve la tabla para que sea responsiva en móviles -->
                <table class="table table-hover align-middle mb-0">
                    <!-- Tabla con efecto hover y alineación centrada -->
                    <thead class="table-light"> <!-- Títulos de la tabla con fondo gris claro -->
                        <tr>
                            <th>ID</th>
                            <th class="ps-4">Foto</th>
                            <th class="ps-4">Nombre del Autor/Instructor</th>
                            <th class="ps-4 text-center">Productos Asociados</th>
                            <th class="text-end pe-4">Acciones</th> <!-- Columna de botones de acción -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authors as $author) <!-- Recorre cada autor de la base de datos -->
                            <tr>
                                <td>{{ $author->id }}</td>
                                <td class="ps-4">
                                    @if($author->photo_path)
                                        <img src="{{ asset('storage/' . $author->photo_path) }}" alt="{{ $author->name }}" class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-secondary shadow-sm" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="ps-4 fw-medium text-dark">{{ $author->name }}</td>
                                <td class="ps-4 text-center">
                                    <span class="badge bg-light text-dark border me-1" title="Libros escritos">
                                        <i class="bi bi-book me-1"></i> {{ $author->books->count() }}
                                    </span>
                                    <span class="badge bg-light text-primary border" title="Cursos grabados">
                                        <i class="bi bi-play-btn me-1"></i> {{ $author->courses->count() }}
                                    </span>
                                </td>

                                <!-- Botones de acción -->
                                <td class="text-end pe-4">
                                    <a href="{{ route('authors.edit', $author) }}"
                                        class="btn btn-outline-secondary btn-sm me-2" title="Editar autor">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>

                                    <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de eliminar este autor?')" title="Eliminar autor">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Estado vacío si no hay Autores -->
            @if($authors->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted display-4"></i>
                    <p class="mt-3 text-secondary mb-0">No hay autores registrados en el sistema.</p>
                </div>
            @endif
        </div>

        @if($authors->hasPages()) <!-- Solo muestra la paginación si hay más de una página -->
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $authors->links('pagination::bootstrap-5') }}
                    <!-- Utiliza el estilo de paginación nativo de Bootstrap 5 -->
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>

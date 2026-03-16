<x-app-layout> <!-- Utiliza el componente lateral de diseño de la aplicación -->
    <x-slot name="header"> <!-- Define el contenido para la sección de cabecera -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Gestión de Cursos') }} <!-- Título principal de la página -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta con sombra suave y sin bordes -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <!-- Cabecera de tarjeta flexible -->
            <h5 class="card-title mb-0">Listado de Cursos en Video</h5> <!-- Título del listado -->
            <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                <!-- Botón primario redondeado -->
                <i class="bi bi-plus-lg me-1"></i> {{ __('Agregar Nuevo Curso') }} <!-- Texto del botón -->
            </a>
        </div>

        <div class="card-body p-0"> <!-- Cuerpo de la tarjeta sin padding para que la tabla sea a sangre -->
            @if(session('success')) <!-- Muestra notificaciones de éxito si las hay -->
                <div class="alert alert-success border-0 rounded-0 mb-0">
                    {{ session('success') }} <!-- Mensaje de confirmación de acción exitosa -->
                </div>
            @endif

            <div class="table-responsive"> <!-- Envuelve la tabla para que sea responsiva en móviles -->
                <table class="table table-hover align-middle mb-0">
                    <!-- Tabla con efecto hover y alineación centrada -->
                    <thead class="table-light"> <!-- Títulos de la tabla con fondo gris claro -->
                        <tr>
                            <th>ID</th>
                            <th class="ps-4">Título</th>
                            <th class="ps-4">Instructor</th>
                            <th class="ps-4">Categoría</th>
                            <th class="ps-4">Precio</th>
                            <th class="text-end pe-4">Acciones</th> <!-- Columna de botones de acción -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course) <!-- Recorre cada curso de la base de datos -->
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td class="ps-4 fw-medium text-dark">{{ $course->title }}</td>
                                <td class="ps-4 text-secondary">{{ $course->author->name ?? 'Sin instructor' }}</td>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border">{{ $course->category->name ?? 'Categoría' }}</span>
                                </td>
                                <td class="ps-4 fw-bold text-primary">${{ number_format($course->price, 2) }}</td>

                                <!-- Botones de acción -->
                                <td class="text-end pe-4">
                                    <a href="{{ route('courses.watch', $course) }}" target="_blank"
                                        class="btn btn-outline-info btn-sm me-2" title="Probar video">
                                        <i class="bi bi-play-btn"></i> Reproducir
                                    </a>

                                    <a href="{{ route('courses.edit', $course) }}"
                                        class="btn btn-outline-secondary btn-sm me-2" title="Editar curso">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>

                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de eliminar este curso? Esta acción no se puede deshacer.')" title="Eliminar curso">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Estado vacío si no hay Cursos -->
            @if($courses->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-camera-video text-muted display-4"></i>
                    <p class="mt-3 text-secondary mb-0">No hay cursos registrados en el sistema.</p>
                </div>
            @endif
        </div>

        @if($courses->hasPages()) <!-- Solo muestra la paginación si hay más de una página -->
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $courses->links('pagination::bootstrap-5') }}
                    <!-- Utiliza el estilo de paginación nativo de Bootstrap 5 -->
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

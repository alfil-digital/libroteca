<x-app-layout> <!-- Utiliza el componente lateral de diseño de la aplicación -->
    <x-slot name="header"> <!-- Define el contenido para la sección de cabecera -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Gestión de Libros') }} <!-- Título principal de la página -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta con sombra suave y sin bordes -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <!-- Cabecera de tarjeta flexible -->
            <h5 class="card-title mb-0">Listado de Libros</h5> <!-- Título del listado -->
            <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                <!-- Botón primario redondeado -->
                <i class="bi bi-plus-lg me-1"></i> {{ __('Agregar Nuevo Libro') }} <!-- Texto del botón -->
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
                            <th>Portada</th>
                            <th class="ps-4">Título</th> <!-- Columna de nombre -->
                            <th class="ps-4">Autor</th> <!-- Columna de nombre -->
                            <th class="ps-4">ISBN</th> <!-- Columna de nombre -->
                            <th class="ps-4">Año</th> <!-- Columna de nombre -->
                            <th class="ps-4">Género</th> <!-- Columna de nombre -->
                            <th class="ps-4">Precio</th> <!-- Columna de nombre -->
                            <th class="text-end pe-4">Acciones</th> <!-- Columna de botones de acción -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book) <!-- Recorre cada usuario de la base de datos -->
                            <tr>
                                <td>{{ $book->id }}</td>
                                <td>
                                    @if($book->cover_path)
                                        <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Portada"
                                            class="rounded shadow-sm" style="width: 40px; height: 55px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center border"
                                            style="width: 40px; height: 55px;">
                                            <i class="bi bi-book text-muted small"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="ps-4 fw-medium text-dark">{{ $book->title }}</td>
                                <td class="ps-4 text-secondary">{{ $book->author->name ?? 'Sin autor' }}</td>
                                <td class="ps-4">{{ $book->isbn }}</td>
                                <td class="ps-4">{{ $book->publication_year }}</td>
                                <td class="ps-4">
                                    <span
                                        class="badge bg-light text-dark border">{{ $book->category->name ?? 'Género' }}</span>
                                </td>
                                <td class="ps-4 fw-bold text-primary">${{ number_format($book->price, 2) }}</td>
                                <!-- Muestra el nombre resaltado -->

                                <!-- Botones de acción -->
                                <td class="text-end pe-4">
                                    @if($book->file_path)
                                        <a href="{{ asset('storage/' . $book->file_path) }}" target="_blank"
                                            class="btn btn-outline-info btn-sm me-2" title="Ver archivo digital">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    @endif

                                    <!-- Botón: Añadir al Carrito -->
                                    <form action="{{ route('cart.add', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm me-2"
                                            title="Agregar al carrito">
                                            <i class="bi bi-cart-plus"></i> Añadir
                                        </button>
                                    </form>

                                    <a href="{{ route('books.edit', $book) }}"
                                        class="btn btn-outline-secondary btn-sm me-2">
                                        Editar
                                    </a>

                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de eliminar este libro?')">
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

        @if($books->hasPages()) <!-- Solo muestra la paginación si hay más de una página -->
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $books->links('pagination::bootstrap-5') }}
                    <!-- Utiliza el estilo de paginación nativo de Bootstrap 5 -->
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
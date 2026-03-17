<x-admin-layout> <!-- Utiliza el componente lateral de diseño de la aplicación -->
    <x-slot name="header"> <!-- Define el contenido para la sección de cabecera -->
        <h2 class="h4 mb-0 text-dark">
            {{ __('Gestión de Usuarios') }} <!-- Título principal de la página -->
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mt-4"> <!-- Tarjeta con sombra suave y sin bordes -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <!-- Cabecera de tarjeta flexible -->
            <h5 class="card-title mb-0">Listado de Usuarios</h5> <!-- Título del listado -->
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                <!-- Botón primario redondeado -->
                <i class="bi bi-plus-lg me-1"></i> {{ __('Agregar Nuevo Usuario') }} <!-- Texto del botón -->
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
                            <th class="ps-4">Nombre</th> <!-- Columna de nombre -->
                            <th>Email</th> <!-- Columna de correo electrónico -->
                            <th>Rol</th> <!-- Columna del rol asignado -->
                            <th>DNI</th> <!-- Columna de identificación única -->
                            <th class="text-end pe-4">Acciones</th> <!-- Columna de botones de acción -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user) <!-- Recorre cada usuario de la base de datos -->
                            <tr>
                                <td class="ps-4 fw-medium text-dark">{{ $user->name }}</td>
                                <!-- Muestra el nombre resaltado -->
                                <td>{{ $user->email }}</td> <!-- Muestra el email -->
                                <td>
                                    <span class="badge bg-info text-dark rounded-pill">
                                        {{ $user->role->name ?? 'Sin Rol' }} <!-- Etiqueta informativa para el rol -->
                                    </span>
                                </td>
                                <td>{{ $user->person->id_number ?? 'No asignado' }}</td>
                                <!-- Muestra el DNI o texto por defecto -->
                                <td class="text-end pe-4"> <!-- Botones de edición y borrado alineados a la derecha -->
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="btn btn-outline-secondary btn-sm me-2">
                                        Editar <!-- Botón de edición con contorno -->
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf <!-- Protección contra ataques CSRF -->
                                        @method('DELETE') <!-- Simula el método HTTP DELETE -->
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                            Eliminar <!-- Botón de borrado con confirmación de seguridad -->
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages()) <!-- Solo muestra la paginación si hay más de una página -->
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                    <!-- Utiliza el estilo de paginación nativo de Bootstrap 5 -->
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
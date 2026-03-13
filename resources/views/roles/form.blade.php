<form method="POST" action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">
    <!-- Define la ruta según si es edición o creación -->
    @csrf <!-- Token de seguridad indispensable en Laravel -->
    @if(isset($role)) <!-- Si existe el objeto role, indicamos que la petición es de tipo PUT -->
        @method('PUT')
    @endif

    <div class="row g-3"> <!-- Fila con espaciado de Bootstrap -->
        <!-- Campo: Nombre del Rol -->
        <div class="col-12">
            <label for="name" class="form-label fw-bold">Nombre del Rol</label> <!-- Etiqueta descriptiva -->
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $role->name ?? '') }}" required autofocus> <!-- Input con validación -->
            @error('name') <!-- Muestra errores si el nombre no es válido o está duplicado -->
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text mt-2">Ejemplos: Administrador, Bibliotecario, Cliente, Lector.</div>
            <!-- Ayuda visual -->
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="mt-4 d-flex justify-content-end align-items-center gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-link text-decoration-none text-secondary">Cancelar</a>
        <!-- Volver sin cambios -->
        <button type="submit" class="btn btn-primary px-4 rounded-pill">
            {{ isset($role) ? 'Actualizar Rol' : 'Crear Rol' }} <!-- Texto dinámico del botón -->
        </button>
    </div>
</form>
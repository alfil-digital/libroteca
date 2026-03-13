<form method="POST" action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
    <!-- Define la ruta según si es edición o creación -->
    @csrf <!-- Token de seguridad indispensable en Laravel -->
    @if(isset($category)) <!-- Si existe el objeto, indicamos que la petición es de tipo PUT -->
        @method('PUT')
    @endif

    <div class="row g-3"> <!-- Fila con espaciado de Bootstrap -->
        <!-- Campo: Nombre de la Categoría -->
        <div class="col-12">
            <label for="name" class="form-label fw-bold">Nombre del Género / Categoría</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $category->name ?? '') }}" required autofocus>
            @error('name') <!-- Muestra errores si el nombre no es válido o está duplicado -->
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text mt-2">Ejemplos: Novela, Ciencia Ficción, Historia, Suspenso.</div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="mt-4 d-flex justify-content-end align-items-center gap-2">
        <a href="{{ route('categories.index') }}" class="btn btn-link text-decoration-none text-secondary">Cancelar</a>
        <!-- Volver sin cambios -->
        <button type="submit" class="btn btn-primary px-4 rounded-pill">
            {{ isset($category) ? 'Actualizar Categoría' : 'Crear Categoría' }} <!-- Texto dinámico del botón -->
        </button>
    </div>
</form>
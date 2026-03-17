<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            <a href="{{ route('authors.index') }}" class="text-decoration-none text-secondary me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            {{ __('Registrar Nuevo Autor / Instructor') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Información del Autor</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Errores de validación global -->
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('authors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Nombre del Autor -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="P. Ej: Gabriel García Márquez">
                        </div>

                        <!-- Foto del Autor -->
                        <div class="mb-3">
                            <label for="photo" class="form-label fw-bold">Foto del Autor / Avatar (Opcional)</label>
                            <input class="form-control" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">Formatos sugeridos: JPG, PNG, WEBP (Max: 2MB).</div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Biografía / Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Escribe aquí un resumen del perfil del autor...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Guardar Autor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

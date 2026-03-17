<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            <a href="{{ route('authors.index') }}" class="text-decoration-none text-secondary me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            {{ __('Editar Autor') }}: <span class="text-primary">{{ $author->name }}</span>
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Modificar Información</h5>
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

                    <form action="{{ route('authors.update', $author) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nombre del Autor -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $author->name) }}" required>
                        </div>

                        <!-- Foto del Autor -->
                        <div class="mb-3">
                            <label for="photo" class="form-label fw-bold">Actualizar Foto / Avatar</label>
                            <div class="d-flex align-items-center mb-2">
                                @if($author->photo_path)
                                    <div class="me-3">
                                        <img src="{{ asset('storage/' . $author->photo_path) }}" alt="{{ $author->name }}" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                @endif
                                <input class="form-control" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
                            </div>
                            <div class="form-text">Si no seleccionas ninguna imagen, se mantendrá la actual.</div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Biografía / Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $author->description) }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Actualizar Autor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

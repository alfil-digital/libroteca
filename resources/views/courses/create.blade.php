<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            <a href="{{ route('courses.index') }}" class="text-decoration-none text-secondary me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            {{ __('Registrar Nuevo Curso en Video') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Detalles del Curso</h5>
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

                    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Título -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Título del Curso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="row">
                            <!-- Autor / Instructor -->
                            <div class="col-md-6 mb-3">
                                <label for="author_id" class="form-label fw-bold">Instructor <span class="text-danger">*</span></label>
                                <select class="form-select" id="author_id" name="author_id" required>
                                    <option value="" disabled selected>Seleccione el instructor...</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Categoría -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Seleccione la categoría...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Precio y Portada -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label fw-bold">Precio ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            
                            <div class="col-md-8 mb-3">
                                <label for="cover_image" class="form-label fw-bold">Imagen de Portada (Opcional)</label>
                                <input class="form-control" type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Formatos: JPG, PNG, WEBP (Max: 2MB).</div>
                            </div>
                        </div>

                        <!-- Video del Curso -->
                        <div class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold d-block">Video del Curso <span class="text-danger">*</span></label>
                            <div class="form-text mb-3">Sube un archivo original MP4/WebM o indica una URL externa directa. (Debes elegir solo uno).</div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="small text-secondary fw-semibold">Opción 1: Subir Archivo</label>
                                    <input class="form-control" type="file" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-secondary fw-semibold">Opción 2: Usar Enlace URL</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://..." value="{{ old('video_url') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Descripción / Temario <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Guardar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

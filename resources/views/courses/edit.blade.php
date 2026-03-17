<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            <a href="{{ route('courses.index') }}" class="text-decoration-none text-secondary me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            {{ __('Editar Curso') }}: <span class="text-primary">{{ $course->title }}</span>
        </h2>
    </x-slot>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <h5 class="card-title mb-0 fw-bold me-auto">Modificar Detalles (ID: {{ $course->id }})</h5>
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

                    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Título -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Título del Curso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $course->title) }}" required>
                        </div>

                        <div class="row">
                            <!-- Autor / Instructor -->
                            <div class="col-md-6 mb-3">
                                <label for="author_id" class="form-label fw-bold">Instructor <span class="text-danger">*</span></label>
                                <select class="form-select" id="author_id" name="author_id" required>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ (old('author_id', $course->author_id) == $author->id) ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Categoría -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $course->category_id) == $category->id) ? 'selected' : '' }}>
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
                                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price', $course->price) }}" required>
                            </div>
                            
                            <div class="col-md-8 mb-3">
                                <label for="cover_image" class="form-label fw-bold">Reemplazar Imagen de Portada</label>
                                <input class="form-control" type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
                                @if($course->cover_path)
                                    <div class="form-text text-success"><i class="bi bi-check-circle"></i> Ya cuenta con portada (Subir otra la reemplazará).</div>
                                @endif
                            </div>
                        </div>

                        <!-- Video del Curso -->
                        <div class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold d-block mb-1">Actualizar Video Módulo</label>
                            <span class="badge bg-secondary mb-3 text-truncate d-block text-start w-100" style="max-width: 100%;">Fuente Actual: {{ $course->video_path }}</span>
                            
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="small text-secondary fw-semibold">Reemplazar por Archivo Local</label>
                                    <input class="form-control" type="file" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-secondary fw-semibold">O reemplazar por URL Externa</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://..." value="{{ old('video_url', (!Str::startsWith($course->video_path, 'http') ? '' : $course->video_path)) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Descripción / Temario <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $course->description) }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Actualizar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

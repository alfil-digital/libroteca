<form method="POST" action="{{ isset($book) ? route('books.update', $book) : route('books.store') }}"
    enctype="multipart/form-data">
    <!-- Define la ruta según si es edición o creación y habilita subida de archivos -->
    @csrf <!-- Token de seguridad indispensable en Laravel -->
    @if(isset($book)) <!-- Si existe el objeto book, indicamos que la petición es de tipo PUT -->
        @method('PUT')
    @endif

    <div class="row g-3"> <!-- Fila con espaciado de Bootstrap -->
        <!-- Campo: Título del Libro -->
        <div class="col-md-8">
            <label for="title" class="form-label fw-bold">Título del Libro</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $book->title ?? '') }}" required autofocus>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: ISBN -->
        <div class="col-md-4">
            <label for="isbn" class="form-label fw-bold">ISBN</label>
            <input type="text" name="isbn" id="isbn" class="form-control @error('isbn') is-invalid @enderror"
                value="{{ old('isbn', $book->isbn ?? '') }}" required>
            @error('isbn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Autor (Selector Dinámico) -->
        <div class="col-md-6">
            <label for="author_id" class="form-label fw-bold">Autor</label>
            <select name="author_id" id="author_id" class="form-select @error('author_id') is-invalid @enderror"
                required>
                <option value="" selected disabled>Seleccione un autor...</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author_id', $book->author_id ?? '') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
            @error('author_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Género / Categoría (Selector Dinámico) -->
        <div class="col-md-6">
            <label for="category_id" class="form-label fw-bold">Género (Categoría)</label>
            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror"
                required>
                <option value="" selected disabled>Seleccione un género...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Editorial -->
        <div class="col-md-6">
            <label for="publisher" class="form-label fw-bold">Editorial</label>
            <input type="text" name="publisher" id="publisher"
                class="form-control @error('publisher') is-invalid @enderror"
                value="{{ old('publisher', $book->publisher ?? '') }}">
            @error('publisher')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Año de Publicación -->
        <div class="col-md-3">
            <label for="publication_year" class="form-label fw-bold">Año</label>
            <input type="number" name="publication_year" id="publication_year"
                class="form-control @error('publication_year') is-invalid @enderror"
                value="{{ old('publication_year', $book->publication_year ?? '') }}" required min="1000"
                max="{{ date('Y') }}">
            @error('publication_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Formato Digital -->
        <div class="col-md-3">
            <label for="format" class="form-label fw-bold">Formato</label>
            <select name="format" id="format" class="form-select @error('format') is-invalid @enderror" required>
                <option value="PDF" {{ old('format', $book->format ?? '') == 'PDF' ? 'selected' : '' }}>PDF</option>
                <option value="EPUB" {{ old('format', $book->format ?? '') == 'EPUB' ? 'selected' : '' }}>EPUB</option>
                <option value="MOBI" {{ old('format', $book->format ?? '') == 'MOBI' ? 'selected' : '' }}>MOBI</option>
            </select>
            @error('format')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Portada del Libro -->
        <div class="col-md-12">
            <label for="cover_image" class="form-label fw-bold">Portada del Libro (Imagen)</label>
            <input type="file" name="cover_image" id="cover_image"
                class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
            @if(isset($book) && $book->cover_path)
                <div class="mt-2 text-center border p-2 rounded bg-light" style="max-width: 200px;">
                    <img src="{{ route('view.cover', ['filename' => $book->cover_path]) }}" alt="Portada actual"
                        class="img-fluid rounded shadow-sm">
                    <div class="small text-muted mt-1">Portada actual</div>
                </div>
            @endif
            @error('cover_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text mt-1">Formatos sugeridos: JPG, PNG, WebP. Tamaño máx: 2MB.</div>
        </div>

        <!-- Campo: Archivo del Libro -->
        <div class="col-md-12">
            <label for="book_file" class="form-label fw-bold">Archivo del Libro (Digital)</label>
            <input type="file" name="book_file" id="book_file"
                class="form-control @error('book_file') is-invalid @enderror" {{ isset($book) ? '' : 'required' }}>
            @if(isset($book) && $book->file_path)
                <div class="form-text text-success">
                    <i class="bi bi-file-earmark-check"></i> Archivo actual:
                    {{ basename($book->title) . "." . basename($book->format) }}
                    ({{ $book->file_size }} KB)
                </div>
            @endif
            @error('book_file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Precio -->
        <div class="col-md-12">
            <label for="price" class="form-label fw-bold">Precio ($)</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" step="0.01" name="price" id="price"
                    class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price', $book->price ?? '') }}" required min="0">
            </div>
            @error('price')
                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="mt-4 d-flex justify-content-end align-items-center gap-2">
        <a href="{{ route('books.index') }}" class="btn btn-link text-decoration-none text-secondary">Cancelar</a>
        <!-- Volver sin cambios -->
        <button type="submit" class="btn btn-primary px-4 rounded-pill">
            {{ isset($book) ? 'Actualizar Libro' : 'Crear Libro' }} <!-- Texto dinámico del botón -->
        </button>
    </div>
</form>
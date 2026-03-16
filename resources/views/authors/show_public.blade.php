<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            <i class="bi bi-person-circle me-2"></i> Perfil del Autor
        </h2>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">
            <!-- Encabezado del Autor -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="h-100 bg-secondary d-flex align-items-center justify-content-center" style="min-height: 400px;">
                                @if($author->photo_path)
                                    <img src="{{ asset('storage/' . $author->photo_path) }}" alt="{{ $author->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="bi bi-person-fill text-white display-1"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 d-flex flex-column justify-content-center p-5">
                            <h1 class="display-4 fw-bold mb-2 text-dark">{{ $author->name }}</h1>
                            <div class="d-flex align-items-center mb-4 text-warning fs-5">
                                @php $avgRating = $author->averageRating(); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="ms-2 text-muted small">({{ number_format($avgRating, 1) }})</span>
                            </div>
                            <p class="lead text-secondary mb-4">
                                {{ $author->description ?: 'Este autor aún no ha redactado su biografía.' }}
                            </p>
                            <div class="d-flex gap-3 mt-auto">
                                <div class="badge bg-white shadow-sm text-dark px-3 py-2 rounded-pill border">
                                    <i class="bi bi-book me-1 text-primary"></i> {{ $author->books->count() }} Libros
                                </div>
                                <div class="badge bg-white shadow-sm text-dark px-3 py-2 rounded-pill border">
                                    <i class="bi bi-play-btn me-1 text-danger"></i> {{ $author->courses->count() }} Cursos
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secciones de Contenido -->
            <div class="row g-5">
                <!-- Columna Izquierda: Portafolio -->
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-4 border-start border-4 border-primary ps-3">Portafolio Literario y Académico</h3>
                    <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
                        @foreach($author->books as $book)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm card-hover transition">
                                <div class="position-absolute p-2" style="z-index: 1;">
                                    <span class="badge bg-secondary rounded-pill small">Libro</span>
                                </div>
                                <div class="bg-light text-center ratio ratio-16x9">
                                    @if($book->cover_path)
                                        <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="object-fit-cover shadow-sm w-100 h-100">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="bi bi-book text-muted fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-1">{{ $book->title }}</h5>
                                    <p class="text-success fw-bold mb-0">${{ number_format($book->price, 2) }}</p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-0">
                                    <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @foreach($author->courses as $course)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm card-hover transition">
                                <div class="position-absolute p-2" style="z-index: 1;">
                                    <span class="badge bg-primary rounded-pill small">Curso</span>
                                </div>
                                <div class="bg-light text-center ratio ratio-16x9">
                                    @if($course->cover_path)
                                        <img src="{{ asset('storage/' . $course->cover_path) }}" alt="{{ $course->title }}" class="object-fit-cover shadow-sm w-100 h-100">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="bi bi-play-btn text-muted fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-1">{{ $course->title }}</h5>
                                    <p class="text-success fw-bold mb-0">${{ number_format($course->price, 2) }}</p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-0">
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- REVIEWS DEL AUTOR -->
                    <h3 class="fw-bold mb-4 border-start border-4 border-warning ps-3">Opiniones de sus alumnos y lectores</h3>
                    <div class="reviews-list">
                        @forelse($author->ratings as $rating)
                            <div class="card border-0 shadow-sm rounded-4 mb-3 p-4">
                                <div class="d-flex gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->user->name) }}&background=random" class="rounded-circle shadow-sm" style="width: 45px; height: 45px;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $rating->user->name }}</h6>
                                            <span class="text-warning small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                        </div>
                                        <p class="text-secondary small mb-0">{{ $rating->comment }}</p>
                                        <small class="text-muted mt-2 d-block">{{ $rating->created_at->format('d M, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small italic">Aún no hay testimonios para este autor.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Columna Derecha: Valorar Instructor -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Valorar al Instructor</h5>
                            @auth
                                @php
                                    $canRate = auth()->user()->orders()->whereHas('orderItems.sellable', function ($query) use ($author) {
                                        $query->where('author_id', $author->id);
                                    })->where('status', 'Completed')->exists();
                                @endphp

                                @if($canRate)
                                    <p class="text-muted small mb-4">Tu opinión ayuda a otros alumnos a elegir bien. Gracias por compartir tu experiencia.</p>
                                    <form action="{{ route('ratings.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rateable_id" value="{{ $author->id }}">
                                        <input type="hidden" name="rateable_type" value="App\Models\Author">
                                        
                                        <div class="mb-3">
                                            <div class="rating-stars fs-3 text-warning">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="stars" value="{{ $i }}" id="astar{{ $i }}" class="d-none" {{ $i == 5 ? 'checked' : '' }}>
                                                    <label for="astar{{ $i }}" class="cursor-pointer"><i class="bi bi-star"></i></label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <textarea name="comment" class="form-control border shadow-sm rounded-3" rows="4" placeholder="¿Cómo fue tu experiencia con los materiales de este autor?"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Enviar Valoración</button>
                                    </form>
                                @else
                                    <div class="alert alert-light border small text-muted">
                                        Solo los alumnos que han adquirido obras de este autor pueden dejar una valoración.
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-3">
                                    <p class="small text-muted mb-3">Inicia sesión para valorar a este instructor.</p>
                                    <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4">Login</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.rating-stars label').forEach(label => {
            label.addEventListener('click', function() {
                const radio = document.getElementById(this.getAttribute('for'));
                const val = parseInt(radio.value);
                const container = this.parentElement;
                
                container.querySelectorAll('i').forEach((icon, index) => {
                    const starPos = 5 - index;
                    if (starPos <= val) {
                        icon.classList.replace('bi-star', 'bi-star-fill');
                    } else {
                        icon.classList.replace('bi-star-fill', 'bi-star');
                    }
                });
            });
        });
    </script>
    <style>
        .cursor-pointer { cursor: pointer; }
        .rating-stars { 
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .rating-stars label:hover,
        .rating-stars label:hover ~ label,
        .rating-stars input:checked ~ label {
            color: #ffc107;
        }
    </style>
</x-app-layout>

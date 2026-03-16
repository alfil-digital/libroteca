<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            Detalle del Libro: {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Portada del Libro -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top" style="top: 2rem;">
                        <div class="bg-light text-center ratio ratio-1x1 d-flex align-items-center justify-content-center">
                            @if($book->cover_path)
                                <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-100 h-100 object-fit-cover shadow-sm">
                            @else
                                <i class="bi bi-book text-muted display-1"></i>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información del Libro -->
                <div class="col-md-7">
                    <div class="ps-md-4">
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Catálogo</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $book->category->name ?? 'General' }}</li>
                            </ol>
                        </nav>

                        <h1 class="display-5 fw-bold text-dark mb-1">{{ $book->title }}</h1>
                        <p class="fs-5 text-secondary mb-4">
                            Por: <a href="{{ route('authors.show_public', $book->author) }}" class="fw-bold text-primary text-decoration-none">{{ $book->author->name }}</a>
                        </p>

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="text-warning fs-4">
                                @php $avgRating = $book->averageRating(); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="text-muted">({{ number_format($avgRating, 1) }}) | {{ $book->ratings->count() }} valoraciones</span>
                        </div>

                        <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                            <div class="row g-3">
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Editorial</small>
                                    <span class="fw-semibold text-dark">{{ $book->publisher ?: 'N/A' }}</span>
                                </div>
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Año</small>
                                    <span class="fw-semibold text-dark">{{ $book->publication_year }}</span>
                                </div>
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Formato</small>
                                    <span class="badge bg-secondary rounded-pill">{{ $book->format }}</span>
                                </div>
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">ISBN</small>
                                    <span class="fw-semibold text-dark">{{ $book->isbn }}</span>
                                </div>
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Tamaño</small>
                                    <span class="fw-semibold text-dark">{{ $book->file_size }} KB</span>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-5">
                            <div class="card-body p-4">
                                @php
                                    $userHasBook = Auth::check() && Auth::user()->hasPurchasedBook($book);
                                @endphp

                                @if($userHasBook)
                                    <div class="alert alert-success text-center mb-4 border-0 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> Ya posees este libro
                                    </div>
                                    <a href="{{ route('download.book', $book) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                                        <i class="bi bi-book-half me-2"></i> Leer Ahora (Descargar)
                                    </a>
                                @else
                                    <div class="text-center mb-4">
                                        <span class="text-muted small d-block mb-1">Precio Final</span>
                                        <h2 class="text-success fw-bold display-6 mb-0">${{ number_format($book->price, 2) }}</h2>
                                    </div>

                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="sellable_id" value="{{ $book->id }}">
                                        <input type="hidden" name="sellable_type" value="{{ App\Models\Book::class }}">
                                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm mb-3 shadow-hover">
                                            <i class="bi bi-cart-plus me-2"></i> Añadir al Carrito
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- SECCIÓN DE VALORACIONES -->
                        <div class="mb-5">
                            <h4 class="fw-bold mb-4">Opiniones de lectores</h4>
                            
                            @auth
                                @php
                                    $hasPurchased = auth()->user()->orders()->whereHas('orderItems', function ($query) use ($book) {
                                        $query->where('sellable_id', $book->id)->where('sellable_type', 'App\Models\Book');
                                    })->where('status', 'Completed')->exists();
                                @endphp

                                @if($hasPurchased)
                                    <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                                        <h6 class="fw-bold mb-3">Deja tu valoración</h6>
                                        <form action="{{ route('ratings.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="rateable_id" value="{{ $book->id }}">
                                            <input type="hidden" name="rateable_type" value="App\Models\Book">
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Puntuación</label>
                                                <div class="rating-stars fs-4 text-warning">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" class="d-none" {{ $i == 5 ? 'checked' : '' }}>
                                                        <label for="star{{ $i }}" class="cursor-pointer"><i class="bi bi-star"></i></label>
                                                    @endfor
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <textarea name="comment" class="form-control border-0 shadow-sm rounded-3" rows="3" placeholder="Cuéntanos qué te pareció este libro..."></textarea>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-dark rounded-pill px-4 btn-sm">Enviar reseña</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth

                            <div class="reviews-list">
                                @forelse($book->ratings as $rating)
                                    <div class="d-flex gap-3 mb-4 last-child-mb-0 pb-4 border-bottom">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->user->name) }}&background=random" class="rounded-circle shadow-sm" style="width: 45px; height: 45px;">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold mb-0">{{ $rating->user->name }}</h6>
                                                <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="text-warning small mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="text-secondary small mb-0">{{ $rating->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted italic small">Aún no hay reseñas para este libro. ¡Sé el primero en opinar!</p>
                                @endforelse
                            </div>
                        </div>

                        <hr class="my-5">

                        <h4 class="fw-bold mb-4">Biografía del Autor</h4>
                        <div class="d-flex align-items-start gap-4 p-4 bg-white border rounded-4 shadow-sm">
                            <img src="{{ $book->author->photo_path ? asset('storage/' . $book->author->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($book->author->name) }}" 
                                 alt="{{ $book->author->name }}" class="rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $book->author->name }}</h5>
                                <p class="text-muted small mb-2 line-clamp-3">{{ $book->author->description ?: 'Este autor aún no ha redactado su biografía.' }}</p>
                                <a href="{{ route('authors.show_public', $book->author) }}" class="btn btn-link p-0 text-decoration-none fw-bold small">Ver perfil completo <i class="bi bi-arrow-right small"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple star rating interaction
        document.querySelectorAll('.rating-stars label').forEach(label => {
            label.addEventListener('click', function() {
                const radio = document.getElementById(this.getAttribute('for'));
                const val = parseInt(radio.value);
                const container = this.parentElement;
                
                container.querySelectorAll('i').forEach((icon, index) => {
                    const starPos = 5 - index; // because they are reversed for CSS or order
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
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
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
        .rating-stars label i::before {
            content: "\f588"; /* star icon code */
            font-family: "bootstrap-icons";
        }
        .rating-stars inputValue:checked + label i::before,
        .rating-stars label:hover i::before,
        .rating-stars label:hover ~ label i::before {
             content: "\f586" !important; /* star-fill icon code */
        }
    </style>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4 text-center bg-light rounded" style="height: 300px; display:flex; align-items:center; justify-content:center;">
                            @if($course->cover_path)
                                <img src="{{ asset('storage/' . $course->cover_path) }}" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;" alt="{{ $course->title }}">
                            @else
                                <i class="bi bi-play-circle text-muted display-1"></i>
                            @endif
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="text-warning fs-5">
                                @php $avgRating = $course->averageRating(); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="text-muted small">({{ number_format($avgRating, 1) }}) | {{ $course->ratings->count() }} valoraciones</span>
                        </div>
                        
                        <h4 class="fw-bold text-dark mb-3">Descripción del Curso</h4>
                        <div class="text-secondary lh-lg mb-4">
                            {!! nl2br(e($course->description)) !!}
                        </div>

                        <hr class="my-5">
                        <h5 class="fw-bold mb-4">Sobre el Instructor</h5>
                        <div class="d-flex align-items-center gap-4 p-4 bg-light rounded-4 border">
                            <img src="{{ $course->author->photo_path ? asset('storage/' . $course->author->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($course->author->name) }}" 
                                 alt="{{ $course->author->name }}" class="rounded-circle shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $course->author->name }}</h6>
                                <p class="text-muted small mb-0">{{ Str::limit($course->author->description, 150) }}</p>
                                <a href="{{ route('authors.show_public', $course->author) }}" class="btn btn-link p-0 text-decoration-none fw-bold small">Ver perfil <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 2rem;">
                    <div class="card-body p-4 bg-white rounded">
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <h2 class="text-success fw-bold display-6 mb-0">${{ number_format($course->price, 2) }}</h2>
                            <span class="badge bg-primary rounded-pill mt-2">Curso en Video</span>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-person text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Instructor</small>
                                    <a href="{{ route('authors.show_public', $course->author) }}" class="fw-bold text-dark text-decoration-none">{{ $course->author->name ?? 'N/A' }}</a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-tags text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Categoría</small>
                                    <span class="fw-bold">{{ $course->category->name ?? 'General' }}</span>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-play-btn text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Formato</small>
                                    <span class="fw-bold">Video Web</span>
                                </div>
                            </li>
                        </ul>

                        @php
                            $userHasCourse = Auth::check() && Auth::user()->hasPurchasedCourse($course);
                        @endphp

                        @if($userHasCourse)
                            <div class="alert alert-success text-center mb-3 border-0 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i> Ya has comprado este curso
                            </div>
                            <a href="{{ route('courses.watch', $course) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm mb-4">
                                <i class="bi bi-play-circle-fill me-2 bg-white text-primary rounded-circle px-1"></i> Ver Curso Ahora
                            </a>

                            <!-- Botón para abrir modal de valoración -->
                            <button type="button" class="btn btn-outline-warning w-100 rounded-pill py-2 fw-bold mb-3" data-bs-toggle="collapse" data-bs-target="#ratingForm">
                                <i class="bi bi-star-fill me-1"></i> Valorar Curso
                            </button>
                            
                            <div class="collapse mb-4" id="ratingForm">
                                <div class="card card-body border-0 bg-light rounded-4 shadow-sm">
                                    <form action="{{ route('ratings.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rateable_id" value="{{ $course->id }}">
                                        <input type="hidden" name="rateable_type" value="App\Models\Course">
                                        
                                        <div class="mb-3">
                                            <div class="rating-stars fs-4 text-warning">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="stars" value="{{ $i }}" id="cstar{{ $i }}" class="d-none" {{ $i == 5 ? 'checked' : '' }}>
                                                    <label for="cstar{{ $i }}" class="cursor-pointer"><i class="bi bi-star"></i></label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <textarea name="comment" class="form-control border-0 shadow-sm rounded-3" rows="3" placeholder="Tu opinión..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-dark w-100 rounded-pill btn-sm">Enviar</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="sellable_id" value="{{ $course->id }}">
                                <input type="hidden" name="sellable_type" value="{{ App\Models\Course::class }}">
                                <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm mb-3 shadow-hover">
                                    <i class="bi bi-cart-plus me-2"></i> Añadir al Carrito
                                </button>
                            </form>

                            @guest
                                <div class="text-center mt-3">
                                    <p class="small text-muted mb-0">¿Ya lo compraste? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Inicia sesión</a></p>
                                </div>
                            @endguest
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN DE RESEÑAS -->
        <div class="row mt-5">
            <div class="col-md-8">
                <h4 class="fw-bold mb-4">¿Qué dicen los estudiantes?</h4>
                <div class="reviews-list">
                    @forelse($course->ratings as $rating)
                        <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->user->name) }}&background=random" class="rounded-circle shadow-sm" style="width: 50px; height: 50px;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $rating->user->name }}</h6>
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
                        <div class="text-center py-5 bg-white rounded-4 border border-dashed">
                            <i class="bi bi-chat-left-text text-muted display-4 mb-3 d-block"></i>
                            <p class="text-muted mb-0">Todavía no hay reseñas. ¡Sé el primero en aprender y valorar!</p>
                        </div>
                    @endforelse
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
        .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    </style>
</x-app-layout>

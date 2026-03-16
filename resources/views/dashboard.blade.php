<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-shop me-2"></i> {{ __('Explorar Libroteca') }}
            </h2>
            <div class="d-flex gap-2">
                <!-- Buscador rápido -->
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control" placeholder="Buscar libro o autor..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <!-- @if(request('search') || request('category'))
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary" title="Limpiar">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif -->
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="row">
            <!-- Sidebar de Filtros -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-filter me-2"></i> Categorías</h6>
                    </div>
                    <div class="list-group list-group-flush small">
                        <a href="{{ route('dashboard') }}"
                            class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                            Todas las Categorías
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('dashboard', ['category' => $category->id] + request()->except('category')) }}"
                                class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                                <span class="badge bg-info text-dark float-end rounded-pill border">
                                    {{ $category->books->count() }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Grilla de Libros -->
            <div class="col-md-9">
                @if($books->isEmpty())
                    <div class="card shadow-sm border-0 text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-journal-x display-1 text-muted"></i>
                            <h4 class="mt-3 text-secondary">No se encontraron libros</h4>
                            <p class="text-muted">Prueba con otros términos de búsqueda o categorías.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill px-4">Ver todos</a>
                        </div>
                    </div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($books as $book)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 card-hover transition">
                                    <div class="position-absolute p-2" style="z-index: 1;">
                                        <span class="badge bg-primary rounded-pill small">{{ $book->category->name }}</span>
                                    </div>

                                    <!-- Imagen de Portada -->
                                    <div class="bg-light text-center rounded-top overflow-hidden d-flex align-items-center justify-content-center"
                                        style="height: 220px;">
                                        @if($book->cover_path)
                                            <img src="{{ route('view.cover', ['filename' => $book->cover_path]) }}"
                                                alt="{{ $book->title }}" class="w-100 h-100 object-fit-cover shadow-sm">
                                        @else
                                            <i class="bi bi-book text-muted display-4"></i>
                                        @endif
                                    </div>

                                    <div class="card-body p-4 d-flex flex-column">
                                        <h5 class="card-title fw-bold text-dark mb-1">{{ $book->title }}</h5>
                                        <p class="card-text text-secondary small mb-3">
                                            Por: <span class="fw-bold">{{ $book->author->name }}</span>
                                        </p>

                                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                            <span class="fs-4 fw-bold text-success">${{ number_format($book->price, 2) }}</span>

                                            <form action="{{ route('cart.add', $book) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-cart-plus me-1"></i> Comprar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .1) !important;
        }

        .transition {
            transition: all 0.3s ease;
        }

        .list-group-item.active {
            z-index: 2;
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</x-app-layout>
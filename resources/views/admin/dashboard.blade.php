<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            <i class="bi bi-speedometer2 me-2"></i> {{ __('Panel de Control Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <!-- Estadísticas Rápidas -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm transition card-hover h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-people text-primary fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small">Usuarios</h6>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['users'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm transition card-hover h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="bi bi-book text-success fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small">Libros</h6>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['books'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm transition card-hover h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="bi bi-play-btn text-info fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small">Cursos</h6>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['courses'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm transition card-hover h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-cash-stack text-warning fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small">Ventas Totales</h6>
                            <h4 class="fw-bold mb-0 text-dark">${{ number_format($stats['revenue'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acceso Directo a ABMs -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 ps-4">
                <h5 class="mb-0 fw-bold">Gestión de Contenidos</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-dark w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center border-dashed">
                            <i class="bi bi-journals fs-2 mb-2"></i>
                            <span class="fw-bold">Gestión de Libros</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-primary w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center border-dashed">
                            <i class="bi bi-camera-video fs-2 mb-2"></i>
                            <span class="fw-bold">Gestión de Cursos</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('authors.index') }}" class="btn btn-outline-info w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center border-dashed">
                            <i class="bi bi-person-badge fs-2 mb-2"></i>
                            <span class="fw-bold">Gestión de Autores</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center border-dashed">
                            <i class="bi bi-tags fs-2 mb-2"></i>
                            <span class="fw-bold">Categorías</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-warning w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center border-dashed text-dark">
                            <i class="bi bi-person-gear fs-2 mb-2"></i>
                            <span class="fw-bold">Control de Usuarios</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-danger w-100 py-3 rounded-4 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center border-dashed">
                            <i class="bi bi-shield-lock fs-2 mb-2"></i>
                            <span class="fw-bold">Roles y Permisos</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .transition {
            transition: all 0.3s ease-in-out;
        }
        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }
        .border-dashed:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }
    </style>
</x-app-layout>

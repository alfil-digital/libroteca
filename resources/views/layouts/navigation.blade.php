<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <!-- Barra de navegación responsiva, fondo blanco y borde inferior -->
    <div class="container"> <!-- Contenedor centrado para alinear el contenido -->
        <!-- Logotipo de la Aplicación -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <x-application-logo class="h-auto" style="width: 36px; fill: currentColor;" />
            <!-- Logo con dimensiones fijas -->
            <span class="ms-2 fw-semibold text-dark">{{ config('app.name', 'Libroteca') }}</span>
            <!-- Nombre de la app al lado del logo -->
        </a>

        <!-- Botón Hamburguesa para Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span> <!-- Icono estándar de menú colapsable -->
        </button>

        <!-- Contenido de la Barra (Links y Dropdown) -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Enlaces de Navegación Izquierda -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('dashboard') }}">
                        {{ __('Catalogo') }} <!-- Enlace al Panel de Control -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('users.index') }}">
                        {{ __('Usuarios') }} <!-- Enlace a la Gestión de Usuarios -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('roles.index') }}">
                        {{ __('Roles') }} <!-- Enlace a la Gestión de Usuarios -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('books.*') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('books.index') }}">
                        {{ __('Libros') }} <!-- Enlace a la Gestión de Libros -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('categories.index') }}">
                        {{ __('Categorías') }} <!-- Enlace a la Gestión de Categorías -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('cart.*') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3 me-1"></i>
                        {{ __('Carrito') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('orders.*') ? 'active fw-bold border-bottom border-primary' : '' }}"
                        href="{{ route('orders.index') }}">
                        <i class="bi bi-bag-check me-1"></i>
                        {{ __('Mis Compras') }}
                    </a>
                </li>
            </ul>

            <!-- Menú de Usuario Derecha -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }} <!-- Muestra el nombre del usuario autenticado -->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    {{ __('Mi Perfil') }} <!-- Enlace para editar el perfil propio -->
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li> <!-- Línea divisoria decorativa -->
                            <li>
                                <!-- Formulario de Cierre de Sesión -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf <!-- Protección CSRF obligatoria -->
                                    <button type="submit" class="dropdown-item text-danger">
                                        {{ __('Cerrar Sesión') }} <!-- Botón para salir del sistema -->
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
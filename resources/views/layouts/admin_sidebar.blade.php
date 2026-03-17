<div id="sidebar-wrapper" class="border-end shadow-sm overflow-auto">
    <div class="sidebar-heading text-white border-bottom border-secondary d-flex align-items-center">
        <x-application-logo class="h-auto" style="width: 24px; fill: white;" />
        <span class="ms-2 fw-bold small uppercase tracking-wider">Libroteca Admin</span>
    </div>
    
    <div class="list-group list-group-flush pt-3">
        <a href="{{ route('admin.dashboard') }}" class="nav-link-admin {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-3"></i> Dashboard
        </a>
        
        <div class="px-3 pt-4 pb-2 text-secondary small fw-bold text-uppercase">Gestión</div>
        
        <a href="{{ route('users.index') }}" class="nav-link-admin {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-3"></i> Usuarios
        </a>
        
        <a href="{{ route('roles.index') }}" class="nav-link-admin {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-3"></i> Roles
        </a>

        <div class="px-3 pt-4 pb-2 text-secondary small fw-bold text-uppercase">Catálogo</div>

        <a href="{{ route('books.index') }}" class="nav-link-admin {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="bi bi-book me-3"></i> Libros
        </a>

        <a href="{{ route('courses.index') }}" class="nav-link-admin {{ request()->routeIs('courses.*') ? 'active' : '' }}">
            <i class="bi bi-camera-video me-3"></i> Cursos
        </a>

        <a href="{{ route('authors.index') }}" class="nav-link-admin {{ request()->routeIs('authors.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge me-3"></i> Autores
        </a>

        <a href="{{ route('categories.index') }}" class="nav-link-admin {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags me-3"></i> Categorías
        </a>
    </div>
</div>

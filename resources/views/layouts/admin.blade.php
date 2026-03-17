<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Libroteca Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 260px;
        }
        body {
            overflow-x: hidden;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            width: var(--sidebar-width);
            margin-left: -var(--sidebar-width);
            transition: margin .25s ease-out;
            background-color: #212529; /* Dark Sidebar */
        }
        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }
        #sidebar-wrapper .list-group {
            width: var(--sidebar-width);
        }
        #page-content-wrapper {
            min-width: 100vw;
        }
        body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }
            body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
                margin-left: -var(--sidebar-width);
            }
        }
        .nav-link-admin {
            color: rgba(255,255,255,.75);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .nav-link-admin:hover {
            color: #fff;
            background-color: rgba(255,255,255,.1);
        }
        .nav-link-admin.active {
            color: #fff;
            background-color: var(--bs-primary);
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('layouts.admin_sidebar')

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-outline-dark btn-sm" id="sidebarToggle"><i class="bi bi-list fs-5"></i></button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-dark me-3 small">
                            <i class="bi bi-shop me-1"></i> Ir a la Tienda
                        </a>

                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <strong>{{ Auth::user()->name }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Header (Slot) -->
            @if (isset($header))
                <header class="bg-white border-bottom py-3 px-4 shadow-sm">
                    {{ $header }}
                </header>
            @endif

            <!-- Main Content -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
        });
    </script>
</body>
</html>

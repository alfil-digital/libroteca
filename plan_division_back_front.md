# Implementation Plan: Clear Separation of Front-end and Back-end

Visually and structurally split the application into a public storefront (Front-end) and a management panel (Back-end/Admin).

## Proposed Changes

### 1. New Admin Layout
Create a dedicated layout for administrative tasks.

#### [NEW] [admin.blade.php](file:///c:/Users/pedie/workspace/libroteca/resources/views/layouts/admin.blade.php)
- Features a sidebar for easy navigation between resources (Users, Books, etc.).
- "Ir a la Tienda" link to return to the public site.
- Clean, dashboard-oriented design.

#### [NEW] [admin_sidebar.blade.php](file:///c:/Users/pedie/workspace/libroteca/resources/views/layouts/admin_sidebar.blade.php)
- Contenida links for: Dashboard, Usuarios, Roles, Libros, Cursos, Autores, Categorías.

#### [NEW] [AdminLayout.php](file:///c:/Users/pedie/workspace/libroteca/app/View/Components/AdminLayout.php)
- Blade component class for the new layout.

### 2. Front-end (Storefront) Layout Refactoring
Keep [AppLayout](file:///c:/Users/pedie/workspace/libroteca/app/View/Components/AppLayout.php#8-18) for the public site but simplify its navigation.

#### [MODIFY] [navigation.blade.php](file:///c:/Users/pedie/workspace/libroteca/resources/views/layouts/navigation.blade.php)
- Remove administrative resource links.
- Keep ONLY a "Panel Admin" link for administrators.
- Focus on Storefront: Catálogo, Carrito, Mis Compras.

### 3. Apply New Layout to Admin Views
Update all views under `resources/views/admin`, and resource index/create/edit views for Books, Courses, etc., to use `x-admin-layout` when accessed for management.

#### [MODIFY] Admin Views
- `admin/dashboard.blade.php`
- `users/*.blade.php`
- `roles/*.blade.php`
- `books/*.blade.php` (Management views)
- `courses/*.blade.php` (Management views)
- `authors/*.blade.php` (Management views)
- `categories/*.blade.php`

**Wait**: Many index views are shared. If I want to keep the separation clean, I should ensure that when an admin is managing a resource, they are in the "Back-end" environment.

## Verification Plan

### Manual Tests
1. **Public Navigation**: Verify the storefront is clean and only shows a "Panel Admin" link for authorized users.
2. **Admin Sidebar**: Enter the admin panel and verify the sidebar is present and functional.
3. **Consistency**: Ensure all management screens (Books index, edit, etc.) use the Admin layout.
4. **Context Switching**: Toggle between "Tienda" and "Panel Admin" via the provided links.



### Resumen de lo que verás:

- Tienda Pública (Front): La barra de navegación superior ahora está limpia, mostrando solo el Catálogo, Carrito y Mis Compras. Si eres administrador, verás un enlace directo al "Panel Admin" en tu menú de usuario.
- Panel de Administración (Back): He creado un entorno totalmente nuevo. Al entrar, verás una barra lateral (Sidebar) oscura y profesional que te permite navegar entre la gestión de Usuarios, Libros, Cursos, Autores y Categorías sin recargar la cabecera pública.
- Navegación Fluida: Desde el panel admin puedes volver a la tienda con un solo clic, y viceversa, manteniendo los contextos visuales bien diferenciados.
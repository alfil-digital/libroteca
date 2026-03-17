# Documentación del Sistema: Libroteca Digital

Este documento proporciona una visión general técnica y funcional del sistema **Libroteca**, una plataforma de venta y lectura de libros digitales y cursos en video.

## 1. Stack Tecnológico
- **Framework:** Laravel 11.x (PHP 8.2+)
- **Frontend:** Blade Templates + Bootstrap 5.3 + Bootstrap Icons
- **Base de Datos:** MariaDB / MySQL
- **Almacenamiento:** Disco local (link simbólico `storage` para archivos públicos)

## 2. Arquitectura de Usuarios y Roles
El sistema utiliza un modelo de autenticación basado en `Breeze` con una extensión de roles:
- **Administrador:** Acceso completo al **Panel Admin**. Puede gestionar usuarios, libros, cursos, categorías y autores.
- **Cliente:** Acceso a la tienda, carrito de compras y sección de "Mis Compras" para visualizar su contenido adquirido.

## 3. Módulos Principales

### A. Catálogo (Libros y Cursos)
- **Books (Libros):** Archivos digitales (PDF/EPUB) con metadata (ISBN, editorial, año) y portadas personalizadas.
- **Courses (Cursos):** Contenido educativo en video (MP4 local o URL externa).
- **Authors (Autores):** Perfiles públicos con biografía, foto y listado de obras.
- **Categories (Categorías):** Organización jerárquica para facilitar el filtrado en la tienda.

### B. Sistema de Carrito de Compras (Híbrido)
- **Invitados:** Los productos se almacenan en la sesión de PHP (`session`).
- **Usuarios Autenticados:** Los productos se persisten en la base de datos (`carts` y `cart_items`).
- **Fusión (Merge):** Al iniciar sesión, el sistema transfiere automáticamente el carrito de invitado a la cuenta del usuario.

### C. Ventas y Distribución
- **Pedidos (Orders):** Registro de transacciones con estado (Pending/Completed).
- **Protección de Contenido:** Solo se habilita la descarga (Libros) o el reproductor (Cursos) mediante comprobación de propiedad en el servidor (`hasPurchasedBook`/`hasPurchasedCourse`).

### D. Sistema de Valoraciones (Polimórfico)
- Implementación de un modelo único `Rating` capaz de calificar Libros, Cursos o Autores.
- Validación: Solo los compradores verificados pueden emitir valoraciones.

## 4. Estructura de Interfaz
- **Front-end (público):** Enfocado en la navegación, búsqueda y conversión. Layout fluido con cabecera limpia.
- **Back-end (administrativo):** Panel dedicado con Sidebar lateral para gestión masiva de datos (ABMs).

## 5. Rutas Clave
- `/catalogo`: Tienda principal.
- `/admin/dashboard`: Punto de entrada backoffice.
- `/libros/{id}` / `/cursos/{id}`: Detalles públicos de productos.
- `/autor/{id}`: Perfil público del autor.

<?php

use App\Http\Controllers\DashboardController; // Importa el controlador del dashboard (Tienda)
use App\Http\Controllers\UserController; // Importa el controlador de usuarios
use App\Http\Controllers\RolController; // Importa el controlador de roles
use App\Http\Controllers\BookController; // Importa el controlador de libros
use App\Http\Controllers\DownloadController; // Importa el controlador de descargas
use App\Http\Controllers\OrderController; // Importa el controlador de pedidos
use App\Http\Controllers\CartController; // Importa el controlador de carrito de compras
use App\Http\Controllers\CategoryController; // Importa el controlador de categorías
use App\Http\Controllers\ProfileController; // Importa el controlador de perfiles
use Illuminate\Support\Facades\Route; // Importa la fachada de rutas

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
|
*/

// Ruta para la página de inicio que retorna la vista welcome
Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/setup', function () {
    try {

        // 2. Crear enlace simbólico manualmente si falla el comando artisan
        $target = storage_path('app/public');
        $shortcut = public_path('storage');

        if (file_exists($shortcut)) {
            // Si ya existe algo ahí, lo borramos para recrearlo
            if (is_link($shortcut)) {
                app()->make('files')->delete($shortcut);
            } else {
                File::deleteDirectory($shortcut);
            }
        }

        // Intentamos el comando oficial
        Artisan::call('storage:link');

        return response()->json([
            'status' => 'success',
            'message' => 'Sistema configurado',
            'storage_link' => file_exists($shortcut) ? 'Creado correctamente' : 'No se pudo crear'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


// Ruta del dashboard, protegida por autenticación y verificación de email
Route::get('/catalogo', [DashboardController::class, 'index'])
    ->name('dashboard');

// Grupo de rutas que requieren que el usuario esté autenticado
Route::middleware('auth')->group(function () {
    // Ruta para mostrar el formulario de edición de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Ruta para procesar la actualización del perfil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Ruta para eliminar la cuenta del perfil
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PANEL DE ADMINISTRACIÓN ---
    Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
        // Dashboard Administrativo
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Rutas de recurso para la gestión administrativa (ABMs)
        Route::resource('users', UserController::class);
        Route::resource('roles', RolController::class);
        Route::resource('authors', \App\Http\Controllers\AuthorController::class);
        Route::resource('books', BookController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('courses', \App\Http\Controllers\CourseController::class);
    });

    // --- RUTAS DE TIENDA Y USUARIO ---
    // Rutas de Pedidos (Compras)
    Route::get('/mis-compras', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mis-compras/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/finalizar-compra', [OrderController::class, 'store'])->name('orders.store');

    // Rutas de Descarga y Visualización
    Route::get('/descargar/{book}', [DownloadController::class, 'download'])->name('download.book');
    Route::get('/cursos/{course}/watch', [\App\Http\Controllers\CourseController::class, 'watch'])->name('courses.watch');
    // Rutas de Valoraciones
    Route::post('/valorar', [\App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
});

// --- RUTAS PÚBLICAS DEL CARRITO Y CONTENIDO ---
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/añadir', [CartController::class, 'add'])->name('cart.add');
Route::delete('/carrito/eliminar/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');

// Detalles Públicos
Route::get('/libros/{book}', [BookController::class, 'show'])->name('books.show_public');
Route::get('/cursos/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show_public');
Route::get('/autor/{author}', [\App\Http\Controllers\AuthorController::class, 'show'])->name('authors.show_public');

// Importa las rutas de autenticación por defecto de Laravel
require __DIR__ . '/auth.php';

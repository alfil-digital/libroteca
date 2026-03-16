<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra la vista del panel de control (Storefront).
     * Muestra todos los libros y cursos disponibles para la compra.
     */
    public function index(Request $request)
    {
        // Consultas iniciales para Libros y Cursos
        $bookQuery = Book::with(['author', 'category']);
        $courseQuery = \App\Models\Course::with(['author', 'category']);

        // Filtro por categoría
        if ($request->has('category') && $request->category != '') {
            $bookQuery->where('category_id', $request->category);
            $courseQuery->where('category_id', $request->category);
        }

        // Buscador por título o autor
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            // Función de búsqueda reutilizable
            $applySearch = function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('author', function ($aq) use ($search) {
                          $aq->where('name', 'like', "%{$search}%");
                      });
                });
            };

            $applySearch($bookQuery);
            $applySearch($courseQuery);
        }

        // Obtenemos todos los resultados coincidentes
        $books = $bookQuery->get();
        $courses = $courseQuery->get();

        // Combinamos las colecciones
        $allItems = $books->concat($courses);

        // Ordenamos por fecha de creación descendente
        $sortedItems = $allItems->sortByDesc('created_at')->values();

        // Paginación manual para la colección combinada
        $perPage = 12;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $sortedItems->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems, 
            $sortedItems->count(), 
            $perPage, 
            $currentPage, 
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
        $items->appends($request->all()); // Mantener los parámetros de búsqueda en la URL de paginación

        // Obtenemos las categorías para el filtro
        $categories = Category::all();

        // Pasamos 'items' a la vista
        return view('dashboard', compact('items', 'categories'));
    }
}

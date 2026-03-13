<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra la vista del panel de control (Storefront).
     * Muestra todos los libros disponibles para la compra.
     */
    public function index(Request $request)
    {
        // Iniciamos la consulta de libros con sus relaciones
        $query = Book::with(['author', 'category']);

        // Filtro por categoría si se proporciona
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Buscador por título o autor
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($aq) use ($search) {
                        $aq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Obtenemos los libros paginados
        $books = $query->paginate(12);

        // Obtenemos las categorías para el filtro
        $categories = Category::all();

        return view('dashboard', compact('books', 'categories'));
    }
}

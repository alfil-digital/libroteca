<?php

namespace App\Http\Controllers;

use App\Models\Category; // Importa el modelo Category
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Muestra el listado de todas las categorías (géneros) registradas.
     */
    public function index()
    {
        // Recupera las categorías con paginación de 10 elementos
        $categories = Category::paginate(10);
        // Retorna la vista de listado
        return view('categories.index', compact('categories'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        // Retorna la vista de creación
        return view('categories.create');
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación del nombre de la categoría
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], [
            'name.unique' => 'Esta categoría ya existe en el sistema.',
            'name.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        // Creación del registro
        Category::create($request->all());

        // Redirección con mensaje de éxito
        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Muestra el detalle de una categoría (no implementado en esta versión).
     */
    public function show(Category $category)
    {
        return redirect()->route('categories.index');
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function edit(Category $category)
    {
        // Retorna la vista de edición pasando el objeto
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualiza la información de una categoría en la base de datos.
     */
    public function update(Request $request, Category $category)
    {
        // Validación permitiendo mantener el nombre actual
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ], [
            'name.unique' => 'Ese nombre de categoría ya está en uso.',
            'name.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        // Actualización de los datos
        $category->update($request->all());

        // Redirección con mensaje de éxito
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría de la base de datos.
     */
    public function destroy(Category $category)
    {
        // Verifica si la categoría tiene libros asociados antes de eliminar
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'No se puede eliminar la categoría porque tiene libros asociados.');
        }

        // Eliminación del registro
        $category->delete();

        // Redirección con confirmación
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada con éxito.');
    }
}

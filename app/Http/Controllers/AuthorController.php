<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    /**
     * Muestra un listado de los autores (Panel ABM).
     */
    public function index()
    {
        // Verificar si el usuario es admin
        if (auth()->user() && auth()->user()->hasRole('administrador')) {
            $authors = Author::paginate(10);
            return view('authors.index', compact('authors'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Muestra el formulario para registrar un nuevo autor.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Almacena un autor nuevo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');

        // Manejo de la subida de la foto
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('authors/photos', 'public');
        }

        Author::create($data);

        return redirect()->route('authors.index')->with('success', 'Autor/Instructor registrado exitosamente.');
    }

    /**
     * Muestra los detalles públicos de un autor ("Portada del Autor").
     */
    public function show(Author $author)
    {
        // Cargamos sus libros y cursos para mostrarlos en su perfil
        $author->load(['books.category', 'courses.category']);
        
        return view('authors.show_public', compact('author'));
    }

    /**
     * Muestra el formulario para editar un autor existente.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Actualiza la información de un autor.
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:authors,name,' . $author->id,
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');

        // Manejo de la actualización de la foto
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($author->photo_path && Storage::disk('public')->exists($author->photo_path)) {
                Storage::disk('public')->delete($author->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('authors/photos', 'public');
        }

        $author->update($data);

        return redirect()->route('authors.index')->with('success', 'Los datos del Autor/Instructor han sido actualizados.');
    }

    /**
     * Elimina un autor.
     */
    public function destroy(Author $author)
    {
        // Validar si el autor tiene libros o cursos asignados antes de eliminar
        if ($author->books()->exists()) {
            return back()->with('error', 'No se puede eliminar este autor porque tiene libros asociados.');
        }

        if ($author->courses()->exists()) {
            return back()->with('error', 'No se puede eliminar este instructor porque tiene cursos en video asociados.');
        }

        // Eliminar foto física si existe
        if ($author->photo_path && Storage::disk('public')->exists($author->photo_path)) {
            Storage::disk('public')->delete($author->photo_path);
        }

        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Autor eliminado del sistema.');
    }
}

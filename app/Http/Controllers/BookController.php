<?php

namespace App\Http\Controllers;

use App\Models\Book; // Importa el modelo Book
use App\Models\Author; // Importa el modelo Author
use App\Models\Category; // Importa el modelo Category
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importa la fachada Storage para manejo de archivos

class BookController extends Controller
{
    /**
     * Muestra el listado de todos los libros registrados.
     */
    public function index()
    {
        // verificar si el usuario es admin
        if (auth()->user()->hasRole('administrador')) {
            $books = Book::with(['author', 'category'])->paginate(10);
            return view('books.index', compact('books'));
        } else {
            return redirect()->route('dashboard');
        }

    }

    /**
     * Muestra el formulario para registrar un nuevo libro.
     */
    public function create()
    {
        // Carga listas de autores y categorías para los selectores
        $authors = Author::all();
        $categories = Category::all();

        // Retorna la vista enviando los datos necesarios
        return view('books.create', compact('authors', 'categories'));
    }

    /**
     * Almacena un libro nuevo en la base de datos con su archivo digital.
     */
    public function store(Request $request)
    {
        // Validación completa incluyendo el archivo binario
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'format' => 'required|string|max:10',
            'book_file' => 'required|file|mimes:pdf,epub,mobi|max:10240', // Máximo 10MB
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Portada máx 2MB
        ]);

        $data = $request->except(['book_file', 'cover_image']);

        // Manejo de la subida del archivo al disco público
        if ($request->hasFile('book_file')) {
            $file = $request->file('book_file');
            // Guarda el archivo en la carpeta 'books' del disco público
            $path = $file->store('books', 'public');

            $data['file_path'] = $path;
            // Convierte el tamaño de bytes a Kilobytes (KB)
            $data['file_size'] = round($file->getSize() / 1024);
        }

        // Manejo de la subida de la imagen de portada
        if ($request->hasFile('cover_image')) {
            $cover = $request->file('cover_image');
            $coverPath = $cover->store('covers', 'public');
            $data['cover_path'] = $coverPath;
        }

        // Creación del registro en la base de datos
        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Libro registrado y archivo subido exitosamente.');
    }

    /**
     * Muestra el detalle de un libro.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Muestra el formulario para editar un libro existente.
     */
    public function edit(Book $book)
    {
        // Carga los datos maestros para los selectores
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'authors', 'categories'));
    }

    /**
     * Actualiza la información de un libro y su archivo si se proporciona uno nuevo.
     */
    public function update(Request $request, Book $book)
    {
        // Validación permitiendo que el archivo sea opcional en la edición
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'format' => 'required|string|max:10',
            'book_file' => 'nullable|file|mimes:pdf,epub,mobi|max:10240',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['book_file', 'cover_image']);

        // Si se subió un nuevo archivo, reemplazamos el anterior
        if ($request->hasFile('book_file')) {
            // Elimina el archivo físico anterior si existe
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
            }

            $file = $request->file('book_file');
            $path = $file->store('books', 'public');

            $data['file_path'] = $path;
            $data['file_size'] = round($file->getSize() / 1024);
        }

        // Manejo de la actualización de la imagen de portada
        if ($request->hasFile('cover_image')) {
            // Eliminar portada anterior
            if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
                Storage::disk('public')->delete($book->cover_path);
            }

            $cover = $request->file('cover_image');
            $coverPath = $cover->store('covers', 'public');
            $data['cover_path'] = $coverPath;
        }

        // Actualización del registro
        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Libro actualizado correctamente.');
    }

    /**
     * Elimina un libro y su archivo físico asociado.
     */
    public function destroy(Book $book)
    {
        // Elimina el archivo físico antes de borrar el registro
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }

        // Elimina la portada física si existe
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }

        // Borrado del registro en la base de datos
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Libro y archivo eliminados con éxito.');
    }
}

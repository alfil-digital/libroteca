<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Muestra un listado de los cursos (Panel ABM).
     */
    public function index()
    {
        // Verificar si el usuario es admin
        if (auth()->user() && auth()->user()->hasRole('administrador')) {
            $courses = Course::with(['author', 'category'])->paginate(10);
            return view('courses.index', compact('courses'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Muestra el formulario para crear un nuevo curso.
     */
    public function create()
    {
        $authors = \App\Models\Author::all();
        $categories = \App\Models\Category::all();

        return view('courses.create', compact('authors', 'categories'));
    }

    /**
     * Almacena un curso nuevo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:102400', // max 100MB video
            'video_url' => 'nullable|url|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['video_file', 'video_url', 'cover_image']);

        // Priorizar archivo de video local sobre URL
        if ($request->hasFile('video_file')) {
            $data['video_path'] = $request->file('video_file')->store('courses/videos', 'public');
        } elseif ($request->filled('video_url')) {
            $data['video_path'] = $request->video_url;
        } else {
            return back()->withInput()->withErrors(['video_file' => 'Debes proporcionar un archivo de video o una URL externa.']);
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_path'] = $request->file('cover_image')->store('courses/covers', 'public');
        }

        Course::create($data);

        return redirect()->route('courses.index')->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Muestra los detalles de un curso específico (Público).
     */
    public function show(Course $course)
    {
        // Retorna la vista de detalles del curso
        return view('courses.show', compact('course'));
    }

    /**
     * Muestra el formulario para editar un curso existente.
     */
    public function edit(Course $course)
    {
        $authors = \App\Models\Author::all();
        $categories = \App\Models\Category::all();

        return view('courses.edit', compact('course', 'authors', 'categories'));
    }

    /**
     * Actualiza la información de un curso en la base de datos.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:102400',
            'video_url' => 'nullable|url|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['video_file', 'video_url', 'cover_image']);

        // Actualizar el video si se proporciona uno nuevo
        if ($request->hasFile('video_file')) {
            // Eliminar video anterior si era local
            if ($course->video_path && !\Illuminate\Support\Str::startsWith($course->video_path, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->video_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($course->video_path);
            }
            $data['video_path'] = $request->file('video_file')->store('courses/videos', 'public');
        } elseif ($request->filled('video_url')) {
            // Eliminar video local si cambia por URL
            if ($course->video_path && !\Illuminate\Support\Str::startsWith($course->video_path, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->video_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($course->video_path);
            }
            $data['video_path'] = $request->video_url;
        }

        // Actualizar portada si hay nueva
        if ($request->hasFile('cover_image')) {
            // Eliminar portada anterior
            if ($course->cover_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->cover_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($course->cover_path);
            }
            $data['cover_path'] = $request->file('cover_image')->store('courses/covers', 'public');
        }

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * Elimina un curso y sus archivos asociados.
     */
    public function destroy(Course $course)
    {
        // Eliminar archivos físicos
        if ($course->video_path && !\Illuminate\Support\Str::startsWith($course->video_path, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->video_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($course->video_path);
        }

        if ($course->cover_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->cover_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($course->cover_path);
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso eliminado exitosamente.');
    }

    /**
     * Muestra el reproductor de video para el curso si fue comprado.
     */
    public function watch(Course $course)
    {
        // Obtiene el usuario actualmente autenticado
        $user = Auth::user();

        // Verifica que exista un usuario y que haya comprado este curso
        if (!$user || !$user->hasPurchasedCourse($course)) {
            // Si no ha comprado el curso, se muestra un error 403 (Prohibido)
            abort(403, 'Debes comprar este curso para poder ver el video.');
        }

        // Si la validación es exitosa, se muestra la vista del reproductor
        return view('courses.watch', compact('course'));
    }
}

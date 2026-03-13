<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importa la fachada DB para transacciones

class RolController extends Controller
{
    /**
     * Muestra el listado de todos los roles registrados en el sistema.
     */
    public function index()
    {
        // Recupera los roles de la base de datos con paginación (10 por página)
        $roles = Role::paginate(10);
        // Retorna la vista de listado enviando la colección de roles
        return view('roles.index', compact('roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     */
    public function create()
    {
        // Retorna la vista de creación de roles
        return view('roles.create');
    }

    /**
     * Almacena un rol recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name', // El nombre es obligatorio y debe ser único
        ]);

        // Creación del rol en la base de datos
        Role::create($request->all());

        // Redirección al listado con mensaje de éxito
        return redirect()->route('roles.index')->with('success', '¡Rol creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // No implementado para roles en esta fase
    }

    /**
     * Muestra el formulario para editar un rol existente.
     */
    public function edit(Role $role)
    {
        // Retorna la vista de edición pasando el objeto rol
        return view('roles.edit', compact('role'));
    }

    /**
     * Actualiza el rol especificado en la base de datos.
     */
    public function update(Request $request, Role $role)
    {
        // Validación: el nombre debe ser único pero ignorando el ID actual
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        // Actualización de los datos del rol
        $role->update($request->all());

        // Redirección al listado con mensaje de éxito
        return redirect()->route('roles.index')->with('success', '¡Rol actualizado correctamente!');
    }

    /**
     * Elimina el rol especificado de la base de datos.
     */
    public function destroy(Role $role)
    {
        // Verifica si el rol tiene usuarios asociados antes de borrar (opcional/seguridad)
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar un rol que tiene usuarios asociados.');
        }

        // Eliminación física del registro
        $role->delete();

        // Redirección con mensaje de confirmación
        return redirect()->route('roles.index')->with('success', 'Rol eliminado con éxito.');
    }
}

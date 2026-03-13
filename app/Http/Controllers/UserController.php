<?php

namespace App\Http\Controllers; // Define el espacio de nombres del controlador

use App\Models\User; // Importa el modelo User
use App\Models\Person; // Importa el modelo Person
use App\Models\Role; // Importa el modelo Role
use Illuminate\Http\Request; // Importa la clase Request para manejar entradas
use Illuminate\Support\Facades\Hash; // Importa la fachada Hash para encriptar contraseñas
use Illuminate\Support\Facades\DB; // Importa la fachada DB para transacciones

class UserController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        // Obtiene los usuarios con sus personas y roles asociados, paginados de 10 en 10
        $users = User::with(['person', 'role'])->paginate(10);
        // Retorna la vista index con los datos de los usuarios
        return view('users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // Obtiene todos los roles disponibles de la base de datos
        $roles = Role::all();
        // Retorna la vista de creación con la lista de roles
        return view('users.create', compact('roles'));
    }

    /**
     * Almacena un usuario recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        // Valida los datos entrantes del formulario
        $request->validate([
            'first_name' => 'required|string|max:255', // El nombre es obligatorio
            'last_name' => 'required|string|max:255', // El apellido es obligatorio
            'id_number' => 'required|string|max:255|unique:people,id_number', // El DNI debe ser único en la tabla people
            'email' => 'required|string|email|max:255|unique:users,email', // El email debe ser único en la tabla users
            'password' => 'required|string|min:8|confirmed', // Contraseña de min 8 caracteres y confirmada
            'role_id' => 'required|exists:roles,id', // El ID del rol debe existir en la tabla roles
        ]);

        // Inicia una transacción para asegurar que se creen ambos registros (Persona y Usuario) o ninguno
        DB::transaction(function () use ($request) {
            // Crea un nuevo registro en la tabla de personas
            $person = Person::create([
                'first_name' => $request->first_name, // Guarda el nombre
                'last_name' => $request->last_name, // Guarda el apellido
                'id_number' => $request->id_number, // Guarda el DNI
                'email' => $request->email, // Guarda el email en el registro de persona
                'phone' => $request->phone, // Guarda el teléfono (puede ser nulo)
                'address' => $request->address, // Guarda la dirección (puede ser nula)
            ]);

            // Crea el usuario vinculado a la persona recién creada
            User::create([
                'name' => $request->first_name . ' ' . $request->last_name, // Combina nombre y apellido para mostrar
                'email' => $request->email, // Usa el email para la autenticación
                'password' => Hash::make($request->password), // Encripta la contraseña
                'person_id' => $person->id, // Vincula con el ID de la persona
                'role_id' => $request->role_id, // Asigna el rol seleccionado
            ]);
        });

        // Redirige a la lista de usuarios con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra el detalle de un usuario específico.
     */
    public function show(User $user)
    {
        // Retorna la vista de detalle con los datos del usuario
        return view('users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(User $user)
    {
        // Obtiene todos los roles para el desplegable
        $roles = Role::all();
        // Carga los datos de la persona asociada
        $user->load('person');
        // Retorna la vista de edición con el usuario y los roles
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza el usuario especificado en la base de datos.
     */
    public function update(Request $request, User $user)
    {

        // Valida los datos para la actualización
        $request->validate([
            'first_name' => 'required|string|max:255', // El nombre es obligatorio
            'last_name' => 'required|string|max:255', // El apellido es obligatorio
            'id_number' => 'required|string|max:255|unique:people,id_number,' . ($user->person_id ?? 'NULL'), // Ignora el actual si existe
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Único excepto para el usuario actual
            'role_id' => 'required|exists:roles,id', // El rol debe ser válido
        ]);

        // Envuelve la actualización en una transacción para mantener la consistencia
        DB::transaction(function () use ($request, $user) {
            // Si el usuario no tiene una persona asociada (creado antes de la migración), la creamos
            if (!$user->person) {
                $person = Person::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'id_number' => $request->id_number,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);

                // Vinculamos la nueva persona al usuario y guardamos
                $user->person_id = $person->id;
                $user->save();

                // Recargamos la relación para que esté disponible en lo que resta del proceso
                $user->load('person');
            } else {
                // Si la persona ya existe, simplemente actualizamos sus datos
                $user->person->update([
                    'first_name' => $request->first_name, // Actualiza el nombre
                    'last_name' => $request->last_name, // Actualiza el apellido
                    'id_number' => $request->id_number, // Actualiza el DNI
                    'email' => $request->email, // Actualiza el email
                    'phone' => $request->phone, // Actualiza el teléfono
                    'address' => $request->address, // Actualiza la dirección
                ]);
            }

            // Actualiza los datos básicos del usuario
            $user->update([
                'name' => $request->first_name . ' ' . $request->last_name, // Reconstruye el nombre completo
                'email' => $request->email, // Mantiene el email sincronizado
                'role_id' => $request->role_id, // Actualiza el rol asignado
            ]);

            // Si el campo de contraseña fue llenado, se actualiza el hash
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }
        });

        // Redirige al listado de usuarios con mensaje de confirmación
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina al usuario de la base de datos.
     */
    public function destroy(User $user)
    {
        // Elimina el registro del usuario (la persona se eliminaría si hay cascada en la BD)
        $user->delete();
        // Redirige con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}

<?php

namespace App\Models; // Define el espacio de nombres de los modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para el uso de factorías
use Illuminate\Foundation\Auth\User as Authenticatable; // Clase base para la autenticación
use Illuminate\Notifications\Notifiable; // Trait para enviar notificaciones
use Laravel\Sanctum\HasApiTokens; // Trait para la gestión de tokens de API con Sanctum

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Usa los traits importados

    /**
     * Atributos que se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',       // Nombre que se muestra del usuario
        'email',      // Correo electrónico del usuario
        'password',   // Contraseña del usuario
        'person_id',  // Clave foránea hacia la tabla de personas
        'role_id',    // Clave foránea hacia la tabla de roles
    ];

    /**
     * Relación con el modelo Person (uno a uno).
     */
    public function person()
    {
        return $this->belongsTo(Person::class); // El usuario pertenece a un registro de persona
    }

    /**
     * Relación con el modelo Role (muchos a uno).
     */
    public function role()
    {
        return $this->belongsTo(Role::class); // El usuario pertenece a un rol específico
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     * 
     * @param string $roleName Nombre del rol a verificar.
     * @return bool
     */
    public function hasRole(string $roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Relación con el modelo Order (uno a muchos).
     */
    public function orders()
    {
        return $this->hasMany(Order::class); // Un usuario puede tener múltiples pedidos
    }

    /**
     * Relación con el modelo Cart (uno a uno).
     */
    public function cart()
    {
        return $this->hasOne(Cart::class); // Un usuario puede tener un carrito activo opcional
    }

    /**
     * Relación con el modelo Download (uno a muchos).
     */
    public function downloads()
    {
        return $this->hasMany(Download::class); // Un usuario puede tener múltiples registros de descarga
    }

    /**
     * Atributos que deben ocultarse en la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',       // Oculta la contraseña en la salida JSON
        'remember_token', // Oculta el token de "recordarme"
    ];

    /**
     * Atributos que deben convertirse a tipos específicos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Convierte la fecha de verificación a objeto datetime
        'password' => 'hashed',             // Trata el campo de contraseña como un hash
    ];
}

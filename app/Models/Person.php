<?php

namespace App\Models; // Espacio de nombres para modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait de factorías
use Illuminate\Database\Eloquent\Model; // Clase base de Eloquent

class Person extends Model
{
    use HasFactory; // Permite el uso de factorías

    /**
     * Atributos que pueden ser llenados mediante asignación masiva.
     */
    protected $fillable = [
        'first_name', // Nombre legal de la persona
        'last_name',  // Apellido legal de la persona
        'id_number',  // Número de identificación único (ej. DNI)
        'email',      // Correo de contacto personal
        'phone',      // Teléfono de contacto personal
        'address',    // Dirección física
    ];

    /**
     * Relación con el modelo User (uno a uno).
     */
    public function user()
    {
        return $this->hasOne(User::class); // Un registro de persona está vinculado a un único usuario del sistema
    }
}

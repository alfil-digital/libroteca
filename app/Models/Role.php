<?php

namespace App\Models; // Espacio de nombres para los modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para el uso de factorías
use Illuminate\Database\Eloquent\Model; // Clase base de Eloquent

class Role extends Model
{
    use HasFactory; // Activa el uso de factorías para este modelo

    /**
     * Atributos que son asignables de forma masiva.
     */
    protected $fillable = ['name']; // Nombre del rol (ej. Admin, Cliente)

    /**
     * Relación con el modelo User (uno a muchos).
     */
    public function users()
    {
        return $this->hasMany(User::class); // Un rol puede ser asignado a múltiples usuarios
    }
}

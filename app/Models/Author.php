<?php

namespace App\Models; // Espacio de nombres del modelo

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de modelo

class Author extends Model
{
    use HasFactory; // Permite usar factorías

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = ['name']; // Nombre completo del autor

    /**
     * Relación con el modelo Book (uno a muchos).
     */
    public function books()
    {
        return $this->hasMany(Book::class); // Un autor puede tener muchos libros registrados en el sistema
    }
}

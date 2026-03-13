<?php

namespace App\Models; // Espacio de nombres de los modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de modelo

class Category extends Model
{
    use HasFactory; // Activa las factorías para este modelo

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = ['name']; // Nombre de la categoría de libros (Género)

    /**
     * Relación con el modelo Book (uno a muchos).
     */
    public function books()
    {
        return $this->hasMany(Book::class); // Una categoría puede contener muchos libros
    }
}

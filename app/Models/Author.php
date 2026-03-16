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
    protected $fillable = ['name', 'photo_path', 'description']; // Nombre completol, ruta de la foto y biografía del autor

    /**
     * Relación con el modelo Book (uno a muchos).
     */
    public function books()
    {
        return $this->hasMany(Book::class); // Un autor puede tener muchos libros registrados en el sistema
    }

    /**
     * Relación con el modelo Course (uno a muchos).
     */
    public function courses()
    {
        return $this->hasMany(Course::class); // Un instructor puede tener muchos cursos en video
    }

    /**
     * Relación con el modelo Rating (polimórfica uno a muchos).
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Calcula el promedio de estrellas de las valoraciones.
     */
    public function averageRating()
    {
        return $this->ratings()->avg('stars') ?: 0;
    }
}

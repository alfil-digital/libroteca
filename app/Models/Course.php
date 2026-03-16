<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory; // Usa el trait de factorías

    /**
     * Atributos que pueden ser llenados mediante asignación masiva.
     */
    protected $fillable = [
        'title',       // Título del curso
        'description', // Descripción del curso
        'price',       // Precio del curso
        'video_path',  // Ruta del archivo o URL del video
        'author_id',   // Clave foránea del autor/instructor
        'category_id', // Clave foránea de la categoría
        'cover_path'   // Ruta de la imagen de portada
    ];

    /**
     * Relación con el modelo Author (muchos a uno).
     */
    public function author()
    {
        return $this->belongsTo(Author::class); // El curso pertenece a un autor
    }

    /**
     * Relación con el modelo Category (muchos a uno).
     */
    public function category()
    {
        return $this->belongsTo(Category::class); // El curso pertenece a una categoría
    }

    /**
     * Relación con el modelo CartItem (uno a muchos polimórfico).
     */
    public function carts()
    {
        return $this->morphMany(CartItem::class, 'sellable'); // Un curso puede estar en múltiples carritos
    }

    /**
     * Relación con el modelo OrderItem (uno a muchos polimórfico).
     */
    public function orders()
    {
        return $this->morphMany(OrderItem::class, 'sellable'); // Un curso puede estar en varios ítems de pedido
    }

    /**
     * Obtiene la URL formateada para embeber (YouTube/Vimeo).
     */
    public function getFormattedVideoUrlAttribute()
    {
        $url = $this->video_path;

        if (!$url) return null;

        // YouTube: Extrae el ID del video y devuelve el link de embed
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
            return "https://www.youtube.com/embed/" . $match[1];
        }

        // Vimeo: Extrae el ID y devuelve el link de player
        if (preg_match('/\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/i', $url, $match)) {
            return "https://player.vimeo.com/video/" . $match[3];
        }

        return $url;
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

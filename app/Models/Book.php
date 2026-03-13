<?php

namespace App\Models; // Espacio de nombres de los modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de los modelos de Eloquent

class Book extends Model
{
    use HasFactory; // Usa el trait de factorías

    /**
     * Atributos que pueden ser llenados mediante asignación masiva.
     */
    protected $fillable = [
        'title',            // Título del libro
        'isbn',             // Código ISBN único
        'publisher',       // Nombre de la editorial
        'publication_year', // Año de publicación
        'price',            // Precio de venta de la copia digital
        'cover_path',       // Ruta de la imagen de portada
        'file_path',        // Ruta interna del archivo digital en el servidor
        'format',           // Formato del archivo (PDF, EPUB, etc.)
        'file_size',        // Tamaño del archivo en KB
        'author_id',        // Clave foránea hacia la tabla de autores
        'category_id',      // Clave foránea hacia la tabla de categorías
    ];

    /**
     * Relación con el modelo Author (muchos a uno).
     */
    public function author()
    {
        return $this->belongsTo(Author::class); // El libro pertenece a un autor
    }

    /**
     * Relación con el modelo Category (muchos a uno).
     */
    public function category()
    {
        return $this->belongsTo(Category::class); // El libro pertenece a una categoría
    }

    /**
     * Relación con el modelo OrderItem (uno a muchos).
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // Un libro puede estar en varios ítems de pedido
    }

    /**
     * Relación con el modelo CartItem (uno a muchos).
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class); // Un libro puede estar en múltiples carritos
    }

    /**
     * Relación con el modelo Download (uno a muchos).
     */
    public function downloads()
    {
        return $this->hasMany(Download::class); // Un libro puede tener muchos registros de descarga
    }
}

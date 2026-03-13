<?php

namespace App\Models; // Espacio de nombres para los modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de modelo

class CartItem extends Model
{
    use HasFactory; // Permite el uso de factorías

    /**
     * Atributos que pueden ser llenados masivamente.
     */
    protected $fillable = [
        'cart_id', // Enlace al carrito de compras
        'book_id', // Enlace al libro añadido al carrito
    ];

    /**
     * Relación con el modelo Cart (muchos a uno).
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class); // El ítem pertenece a un carrito específico
    }

    /**
     * Relación con el modelo Book (muchos a uno).
     */
    public function book()
    {
        return $this->belongsTo(Book::class); // El ítem se refiere a un libro específico
    }
}

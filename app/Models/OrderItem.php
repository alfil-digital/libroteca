<?php

namespace App\Models; // Espacio de nombres para modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de modelo

class OrderItem extends Model
{
    use HasFactory; // Permite usar factorías

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'order_id',   // Referencia al pedido padre
        'book_id',    // Referencia al libro comprado
        'unit_price', // Precio del libro en el momento de la compra
    ];

    /**
     * Relación con el modelo Order (muchos a uno).
     */
    public function order()
    {
        return $this->belongsTo(Order::class); // El ítem pertenece a un pedido específico
    }

    /**
     * Relación con el modelo Book (muchos a uno).
     */
    public function book()
    {
        return $this->belongsTo(Book::class); // El ítem se refiere a un libro específico
    }
}

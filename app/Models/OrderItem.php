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
        'order_id',      // Referencia al pedido padre
        'sellable_id',   // ID del producto comprado
        'sellable_type', // Tipo de modelo del producto
        'unit_price',    // Precio en el momento de la compra
    ];

    /**
     * Relación con el modelo Order (muchos a uno).
     */
    public function order()
    {
        return $this->belongsTo(Order::class); // El ítem pertenece a un pedido específico
    }

    /**
     * Relación polimórfica (puede ser Book o Course).
     */
    public function sellable()
    {
        return $this->morphTo(); // Permite referenciar cualquier modelo 'sellable'
    }
}

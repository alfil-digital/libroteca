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
        'cart_id',       // Enlace al carrito de compras
        'sellable_id',   // ID del producto (libro o curso)
        'sellable_type', // Tipo de producto asociado
    ];

    /**
     * Relación con el modelo Cart (muchos a uno).
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class); // El ítem pertenece a un carrito específico
    }

    /**
     * Relación polimórfica (puede ser Book o Course).
     */
    public function sellable()
    {
        return $this->morphTo(); // Permite referenciar cualquier modelo 'sellable'
    }
}

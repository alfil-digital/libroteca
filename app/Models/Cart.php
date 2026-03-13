<?php

namespace App\Models; // Espacio de nombres del modelo

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de Eloquent

class Cart extends Model
{
    use HasFactory; // Permite el uso de factorías

    /**
     * Atributos que se pueden llenar de forma masiva.
     */
    protected $fillable = [
        'user_id',       // El usuario que posee este carrito de sesión
        'last_activity', // Marca de tiempo para rastrear la última modificación
    ];

    /**
     * Relación con el modelo User (muchos a uno).
     */
    public function user()
    {
        return $this->belongsTo(User::class); // El carrito está asociado a un único usuario
    }

    /**
     * Relación con el modelo CartItem (uno a muchos).
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class); // El carrito contiene múltiples ítems o libros
    }
}

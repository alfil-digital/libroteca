<?php

namespace App\Models; // Espacio de nombres del modelo

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de Eloquent

class Order extends Model
{
    use HasFactory; // Permite el uso de factorías

    /**
     * Atributos que se pueden llenar mediante asignación masiva.
     */
    protected $fillable = [
        'user_id',      // Enlace al usuario que realizó el pedido
        'order_date',   // Fecha y hora en que se finalizó el pedido
        'total_amount', // Precio total pagado por el pedido
        'status',       // Estado actual (Pendiente, Completado, Cancelado)
    ];

    /**
     * Relación con el modelo User (muchos a uno).
     */
    public function user()
    {
        return $this->belongsTo(User::class); // El pedido pertenece a un usuario específico
    }

    /**
     * Relación con el modelo OrderItem (uno a muchos).
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // Un pedido consta de múltiples ítems (libros)
    }
}

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
    /**
     * Fusiona el carrito de sesión con el carrito de la base de datos de un usuario.
     */
    public static function mergeSessionCart($userId)
    {
        $sessionCart = session()->get('cart', []);
        
        if (empty($sessionCart)) {
            return;
        }

        $cart = self::firstOrCreate(
            ['user_id' => $userId],
            ['last_activity' => now()]
        );

        foreach ($sessionCart as $item) {
            // Verificar si el ítem ya existe en el carrito de la BD para evitar duplicados
            $exists = CartItem::where('cart_id', $cart->id)
                ->where('sellable_id', $item['sellable_id'])
                ->where('sellable_type', $item['sellable_type'])
                ->exists();

            if (!$exists) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'sellable_id' => $item['sellable_id'],
                    'sellable_type' => $item['sellable_type'],
                ]);
            }
        }

        // Limpiar el carrito de la sesión después de la fusión
        session()->forget('cart');
    }
}

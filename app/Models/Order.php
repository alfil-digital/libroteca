<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory; // Permite el uso de factorías

    // Constantes para estados de pago
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_APPROVED = 'approved';
    const PAYMENT_REJECTED = 'rejected';
    const PAYMENT_CANCELLED = 'cancelled';

    // Constantes para estados del pedido
    const STATUS_PENDING = 'Pending';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';

    /**
     * Atributos que se pueden llenar mediante asignación masiva.
     */
    protected $fillable = [
        'user_id',      // Enlace al usuario que realizó el pedido
        'order_date',   // Fecha y hora en que se finalizó el pedido
        'total_amount', // Precio total pagado por el pedido
        'status',       // Estado actual (Pendiente, Completado, Cancelado)
        'payment_id',   // ID del pago en Mercado Pago
        'payment_status', // Estado del pago (pending, approved, rejected, cancelled)
        'external_reference', // Referencia externa para Mercado Pago
        'payment_method', // Método de pago utilizado
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

    /**
     * Verifica si el pedido está pagado
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_APPROVED;
    }

    /**
     * Verifica si el pago está pendiente
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }

    /**
     * Actualiza el estado del pago
     */
    public function updatePaymentStatus(string $status, ?string $paymentId = null): void
    {
        $this->payment_status = $status;

        if ($paymentId) {
            $this->payment_id = $paymentId;
        }

        // Actualizar estado del pedido basado en el pago
        switch ($status) {
            case self::PAYMENT_APPROVED:
                $this->status = self::STATUS_COMPLETED;
                // Enviar email cuando se aprueba el pago
                $this->notifyPaymentApproved();
                break;
            case self::PAYMENT_REJECTED:
            case self::PAYMENT_CANCELLED:
                $this->status = self::STATUS_CANCELLED;
                break;
            case self::PAYMENT_PENDING:
                $this->status = self::STATUS_PENDING;
                break;
        }

        $this->save();
    }

    /**
     * Notificar al usuario que su pago fue aprobado
     */
    private function notifyPaymentApproved(): void
    {
        try {
            $user = $this->user;
            if ($user && $user->email) {
                Mail::to($user->email)->queue(new \App\Mail\OrderPaidMail($this));
            }
        } catch (\Exception $e) {
            Log::error('Error enviando email de pago aprobado', [
                'order_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Obtiene los items formateados para Mercado Pago
     */
    public function getItemsForMercadoPago(): array
    {
        $items = [];

        foreach ($this->orderItems as $orderItem) {
            $sellable = $orderItem->sellable;

            if ($sellable) {
                $items[] = [
                    'title' => $sellable->title ?? $sellable->name ?? 'Producto',
                    'quantity' => 1, // Cada OrderItem representa una unidad
                    'unit_price' => $orderItem->unit_price,
                    'description' => $sellable->description ?? null,
                    'picture_url' => $sellable->image_url ?? $sellable->cover_url ?? null,
                ];
            }
        }

        return $items;
    }
}

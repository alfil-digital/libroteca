<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaidMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pago Confirmado - Pedido #{$this->order->id}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.order-paid',
            with: [
                'order' => $this->order,
                'orderNumber' => str_pad($this->order->id, 5, '0', STR_PAD_LEFT),
                'total' => number_format($this->order->total_amount, 2),
                'paymentMethod' => $this->getPaymentMethodName($this->order->payment_method),
                'itemsCount' => $this->order->orderItems->count(),
                'downloadUrl' => route('orders.show', $this->order),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Obtener el nombre del método de pago
     */
    private function getPaymentMethodName(?string $method): string
    {
        return match ($method) {
            'credit_card' => 'Tarjeta de Crédito',
            'rapipago' => 'Rapipago/Pago en Efectivo',
            default => 'Mercado Pago',
        };
    }
}

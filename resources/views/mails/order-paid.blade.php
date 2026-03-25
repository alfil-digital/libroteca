<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Confirmado</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            font-size: 14px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .detail-row strong {
            color: #333;
        }
        .detail-row span {
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .items-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            margin-top: 20px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }
        .button:hover {
            background-color: #5568d3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 8px 8px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Pago Confirmado!</h1>
            <p>Tu compra ha sido procesada exitosamente</p>
        </div>

        <div class="content">
            <!-- Información del Pedido -->
            <div class="section">
                <div class="section-title">Detalles de tu Pedido</div>
                <div class="order-details">
                    <div class="detail-row">
                        <strong>Número de Pedido:</strong>
                        <span>#{{ $orderNumber }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Fecha:</strong>
                        <span>{{ $order->order_date->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Estado del Pago:</strong>
                        <span><span class="badge badge-success">PAGADO</span></span>
                    </div>
                    <div class="detail-row">
                        <strong>Método de Pago:</strong>
                        <span>{{ $paymentMethod }}</span>
                    </div>
                </div>
            </div>

            <!-- Artículos Comprados -->
            @if($itemsCount > 0)
            <div class="section">
                <div class="section-title">Artículos Comprados</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th style="text-align: right;">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->sellable->title ?? $item->sellable->name ?? 'Producto' }}</strong>
                                @if($item->sellable->author)
                                <br><small style="color: #666;">{{ $item->sellable->author->name }}</small>
                                @endif
                            </td>
                            <td>
                                @if($item->sellable_type === 'App\Models\Book')
                                    Libro Digital
                                @else
                                    Curso en Video
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <strong>${{ number_format($item->unit_price, 2) }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2">TOTAL:</td>
                            <td style="text-align: right;">${{ $total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif

            <!-- Próximos Pasos -->
            <div class="section">
                <div class="section-title">Acceder a tus Compras</div>
                <p>Ya puedes acceder a tus libros digitales y cursos en tu cuenta:</p>
                <a href="{{ $downloadUrl }}" class="button">Ver mis Compras</a>
            </div>

            <!-- Información Adicional -->
            <div class="section">
                <div class="section-title">¿Necesitas Ayuda?</div>
                <p>Si tienes cualquier pregunta sobre tu compra, no dudes en contactarnos. Estamos aquí para ayudarte.</p>
                <p style="margin: 10px 0; color: #666;">
                    Email de soporte: <strong>soporte@ejemplo.com</strong>
                </p>
            </div>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>

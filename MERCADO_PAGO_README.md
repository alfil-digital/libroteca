# Integración Mercado Pago

Esta aplicación incluye integración completa con Mercado Pago para procesar pagos en línea.

## Configuración

### 1. Variables de Entorno

Copia las variables del `.env.example` a tu `.env`:

```bash
cp .env.example .env
```

Configura las siguientes variables en tu `.env`:

```env
# Credenciales de Mercado Pago (obténlas de https://www.mercadopago.com/developers)
MERCADO_PAGO_ACCESS_TOKEN=TEST-1234567890123456-123456-1234567890123456789
MERCADO_PAGO_PUBLIC_KEY=TEST-12345678-1234-1234-1234-123456789012

# Ambiente: 'sandbox' para pruebas, 'production' para producción
MERCADO_PAGO_ENVIRONMENT=sandbox

# Moneda por defecto
MERCADO_PAGO_CURRENCY=ARS

# URLs de retorno después del pago
MERCADO_PAGO_SUCCESS_URL=http://localhost/payment/success
MERCADO_PAGO_FAILURE_URL=http://localhost/payment/failure
MERCADO_PAGO_PENDING_URL=http://localhost/payment/pending

# URL para webhooks (debe ser HTTPS en producción)
MERCADO_PAGO_WEBHOOK_URL=http://localhost/api/webhooks/mercadopago
```

### 2. Credenciales de Mercado Pago

1. Ve a [Mercado Pago Developers](https://www.mercadopago.com/developers)
2. Crea una aplicación
3. Obtén el `Access Token` y `Public Key`
4. Para pruebas usa las credenciales de test

### 3. Configuración de Webhooks

En el panel de Mercado Pago:
1. Ve a "Webhooks" en tu aplicación
2. URL: `https://tu-dominio.com/api/webhooks/mercadopago`
3. Eventos: `payment`

## Uso

### Crear un Pago

```php
use App\Services\MercadoPagoService;

$mercadoPago = app(MercadoPagoService::class);

$preference = $mercadoPago->createPreference([
    [
        'title' => 'Libro de Programación',
        'quantity' => 1,
        'unit_price' => 29.99,
        'description' => 'Libro sobre desarrollo web'
    ]
], [
    'name' => 'Juan',
    'surname' => 'Pérez',
    'email' => 'juan@example.com'
], 'ORDER-123');

// Redirigir al checkout
return redirect($preference['init_point']);
```

### Verificar Estado de Pago

```php
$payment = $mercadoPago->getPayment('123456789');

// Estados posibles: approved, pending, rejected, cancelled
if ($payment['status'] === 'approved') {
    // Pago aprobado
}
```

### Página de Checkout

Visita `/checkout` para ver el formulario de ejemplo.

## Rutas Disponibles

| Ruta | Método | Descripción |
|------|--------|-------------|
| `/checkout` | GET | Formulario de checkout |
| `/payment/success` | GET | Página de pago exitoso |
| `/payment/failure` | GET | Página de pago fallido |
| `/payment/pending` | GET | Página de pago pendiente |
| `/api/mercadopago/preference` | POST | Crear preferencia de pago |
| `/api/mercadopago/payment/{id}` | GET | Obtener estado de pago |
| `/api/webhooks/mercadopago` | POST | Webhook de Mercado Pago |

## Estructura de Archivos

```
app/
├── Services/
│   └── MercadoPagoService.php     # Servicio principal
├── Http/Controllers/
│   └── MercadoPagoController.php  # Controlador
config/
├── mercadopago.php                # Configuración
routes/
├── mercadopago.php                # Rutas
resources/views/mercadopago/
├── checkout.blade.php             # Formulario de pago
├── success.blade.php              # Pago exitoso
├── failure.blade.php              # Pago fallido
└── pending.blade.php              # Pago pendiente
```

## Estados de Pago

- `approved`: Pago aprobado
- `pending`: Pago pendiente de aprobación
- `rejected`: Pago rechazado
- `cancelled`: Pago cancelado

## Webhooks

Los webhooks se procesan automáticamente en `MercadoPagoController@webhook`. Implementa la lógica de negocio según el estado del pago.

## Producción

Para producción:
1. Cambia `MERCADO_PAGO_ENVIRONMENT=production`
2. Usa credenciales de producción
3. Configura HTTPS
4. Actualiza URLs de retorno y webhook

## Troubleshooting

### Error: "Invalid access token"
- Verifica que el ACCESS_TOKEN sea correcto
- Asegúrate de usar credenciales del ambiente correcto (test/prod)

### Error: "Invalid public key"
- Verifica que la PUBLIC_KEY sea correcta
- Se usa en el frontend para inicializar el SDK

### Webhooks no llegan
- La URL del webhook debe ser HTTPS en producción
- Verifica que esté configurada correctamente en Mercado Pago
- Revisa los logs de Laravel

## Soporte

Para más información consulta la [documentación de Mercado Pago](https://www.mercadopago.com/developers).
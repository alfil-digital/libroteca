# 📱 Integración de Mercado Pago - Sistema de Pagos Completo

## 🎯 Resumen General

El sistema está completamente integrado con **Mercado Pago** como proveedor de pagos, con soporte para múltiples métodos:
- **Tarjeta de Crédito** (con cuotas hasta 12)
- **Rapipago / Pago en Efectivo**
- **Todos los métodos** (opción por defecto)

---

## 🔄 Flujo de Compra Completo

### 1. Carrito de Compras
- Usuario agrega libros/cursos al carrito
- En `resources/views/cart/index.blade.php`:
  - Selecciona **método de pago**:
    - `all` (todos los métodos)
    - `credit_card` (tarjeta de crédito)
    - `rapipago` (pago en efectivo)
  - Hace clic en **"Finalizar Compra"**

### 2. Creación de Orden
- **Controlador**: `app/Http/Controllers/OrderController@store`
- Acciones:
  1. Valida que el carrito no esté vacío
  2. Calcula el total
  3. Crea `Order` con:
     - `status = 'Pending'`
     - `payment_status = 'pending'`
     - `payment_method` (seleccionado por el usuario)
     - `external_reference = 'ORDER-{timestamp}-{userid}'`
  4. Crea `OrderItem` para cada producto
  5. Genera **preferencia de Mercado Pago** con método seleccionado
  6. Limpia el carrito
  7. **Redirige** al checkout de Mercado Pago

### 3. Checkout Mercado Pago
- **Servicio**: `app/Services/MercadoPagoService@createPreference`
- Configura métodos de pago según selección:
  - `credit_card`: Excluye tickets y ATM, permite hasta 12 cuotas
  - `rapipago`: Solo muestra métodos de pago en efectivo
  - `all`: Todos disponibles (por defecto)
- URLs de retorno:
  - **Success**: `/payment/success`
  - **Failure**: `/payment/failure`
  - **Pending**: `/payment/pending`

### 4. Pago y Retorno
El usuario completa el pago en Mercado Pago y es redirigido a:
- ✅ **Success** si pago aprobado
- ❌ **Failure** si pago rechazado
- ⏳ **Pending** si pago pendiente

### 5. Webhook de Mercado Pago
**Endpoint**: `POST /api/webhooks/mercadopago`

Cuando Mercado Pago confirma el pago:
1. Busca la orden por `external_reference`
2. Obtiene detalles del pago
3. Actualiza:
   - `order->payment_id`
   - `order->payment_status`
   - `order->payment_method` (del pago real)
   - `order->status` (según estado de pago)
4. **Si pagado**: Envía email de confirmación al usuario

---

## 📧 Notificaciones por Email

### Mailable: `OrderPaidMail`
**Archivo**: `app/Mail/OrderPaidMail.php`

Se envía **automáticamente** cuando el pago es aprobado (`payment_status = 'approved'`)

Contenido del email:
- ✅ Confirmación de pago
- 📋 Detalles del pedido (número, fecha, total)
- 💳 Método de pago utilizado
- 📚 Lista de productos comprados
- 🔗 Enlace para acceder a las compras
- 📞 Información de soporte

**Plantilla**: `resources/views/mails/order-paid.blade.php`

---

## 🗂️ Estructura de Base de Datos

### Tabla `orders`
```sql
- id (PK)
- user_id (FK)
- order_date (timestamp)
- total_amount (decimal)
- status (enum: 'Pending', 'Completed', 'Cancelled')
- payment_id (string) -- ID del pago en Mercado Pago
- payment_status (enum: 'pending', 'approved', 'rejected', 'cancelled')
- external_reference (string) -- Vínculo con preferencia de MP
- payment_method (string) -- 'all', 'credit_card', 'rapipago'
```

---

## 🎮 Interfaz de Usuario

### `orders.index` (Mis Compras)
Muestra tabla con:
- **Nº Pedido**: ID del pedido
- **Fecha**: Fecha de creación
- **Artículos**: Cantidad de productos
- **Estado**: Badge (Pagado / Pendiente / Rechazado)
- **Método**: Badge con método de pago (💳 Tarjeta / 💰 Rapipago)
- **Total**: Monto pagado
- **Acciones**: Botón "Ver Detalle"

### `orders.show` (Detalle del Pedido)
Muestra:
- **Libros/Cursos comprados**:
  - Título, autor, precio
  - Botones de descarga (libros) o visualización (cursos)
- **Resumen de compra**:
  - Estado del pedido
  - Estado de pago
  - Método de pago utilizado
  - Botón **"Reintentar Pago"** (si aplica):
    - Visible cuando: `payment_status ∈ [pending, rejected, cancelled]`
    - Crea nueva preferencia y redirige a MP

### `mercadopago.success|failure|pending`
Páginas de confirmación con:
- Icono y mensaje de estado
- Datos de la orden
- Estado del pago
- Enlaces de navegación (carrito, mis compras, inicio)

---

## 🔧 Métodos de Pago - Configuración

### Tarjeta de Crédito
```php
'payment_methods' => [
    'excluded_payment_types' => [
        ['id' => 'ticket'],
        ['id' => 'atm'],
    ],
    'installments' => 12,
]
```
- Acepta solo tarjetas
- Permite pago en cuotas (1-12)

### Rapipago / Pago en Efectivo
```php
'payment_methods' => [
    'excluded_payment_types' => [
        ['id' => 'credit_card'],
        ['id' => 'debit_card'],
        ['id' => 'atm'],
    ],
    'installments' => 1,
]
```
- Solo métodos de pago en efectivo (Rapipago, Pagofácil, Bapro)
- Sin cuotas (pago único)

### Todos los Métodos (Default)
- Mercado Pago decide qué métodos mostrar según la cuenta
- Máxima flexibilidad para el usuario

---

## 🔐 Seguridad

### CSRF Protection
Todos los formularios incluyen `@csrf`

### Validación de Autenticación
- Solo usuarios autenticados pueden realizar compras
- Verificación de propiedad: `order->user_id === auth()->id()`

### Referencia Externa Única
```php
'external_reference' => 'ORDER-' . time() . '-' . Auth::id()
```
Evita colisiones y proporciona trazabilidad

### Webhook Signature Validation
(Recomendado agregar en producción)
```php
// En MercadoPagoController@webhook
// Validar firma del webhook de Mercado Pago
```

---

## 📊 Flujo de Estados

```
Carrito
   ↓
[Crear Orden] → status='Pending', payment_status='pending'
   ↓
[Generar Preferencia MP] → external_reference
   ↓
[Checkout MP] → Usuario paga
   ↓
[Webhook Recibido] ← Mercado Pago confirma
   ↓
   ├─→ Aprobado → status='Completed', payment_status='approved'
   │                ↓
   │          [Email enviado]
   │
   ├─→ Rechazado → status='Cancelled', payment_status='rejected'
   │
   └─→ Pendiente → status='Pending', payment_status='pending'
                    ↓
                [Usuario puede reintentar]
```

---

## 🧪 Testing

### Flujo Sandbox
1. Crear orden con método "Tarjeta de Crédito"
2. En Mercado Pago, usar tarjeta de prueba:
   - `4111 1111 1111 1111`
   - Exp: `12/25` | CVC: `123`
3. Simular webhook:
   ```bash
   curl -X POST http://localhost:8000/api/webhooks/mercadopago \
     -H "Content-Type: application/json" \
     -d '{
       "topic": "payment",
       "id": "123456789"
     }'
   ```
4. Verificar:
   - `order.payment_status = 'approved'`
   - `order.status = 'Completed'`
   - Email en cola (check `storage/logs/laravel.log`)

### Prueba de Reintento
1. Crear orden
2. Simular fallo en webhook
3. En `orders.show`, ver botón "Reintentar Pago"
4. Hacer clic → nueva preferencia generada
5. Completar pago exitosamente

---

## 📝 Configuración Requerida

### `.env`
```
MERCADOPAGO_PUBLIC_KEY=APP_USR_xxxxxx
MERCADOPAGO_ACCESS_TOKEN=APP_USR_xxxxxx
MERCADOPAGO_WEBHOOK_URL=/api/webhooks/mercadopago
MERCADOPAGO_SUCCESS_URL=/payment/success
MERCADOPAGO_FAILURE_URL=/payment/failure
MERCADOPAGO_PENDING_URL=/payment/pending
```

### `config/mercadopago.php`
```php
'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
'currency' => 'ARS',
'webhook_url' => env('MERCADOPAGO_WEBHOOK_URL'),
'success_url' => env('MERCADOPAGO_SUCCESS_URL'),
'failure_url' => env('MERCADOPAGO_FAILURE_URL'),
'pending_url' => env('MERCADOPAGO_PENDING_URL'),
```

---

## 🚀 Próximas Mejoras

1. **Firma de Webhook**: Validar `x-signature` para seguridad
2. **Email de Pago Fallido**: Notificar al usuario si pago rechazado
3. **Reporte de Auditoría**: Registrar historial de intentos de pago
4. **Reenvío Automático**: Retry de webhook si falla
5. **Recibo PDF**: Adjuntar en email de confirmación
6. **Métodos Adicionales**: Integrar Apple Pay, Google Pay (si aplica)
7. **Webhook Reintento**: Implementar queue jobs con reintentos exponenciales

---

## 📞 Soporte

Para contacto o issues relacionados con pagos:
- Email: soporte@ejemplo.com
- Documentación Mercado Pago: https://developers.mercadopago.com


<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Mis Compras') }} <!-- Título de la página de historial -->
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if($orders->isEmpty())
                    <!-- Estado vacío: No hay pedidos -->
                    <div class="text-center py-5">
                        <i class="bi bi-bag-x text-muted display-1"></i>
                        <h4 class="mt-3 text-secondary">Aún no has realizado ninguna compra</h4>
                        <p class="text-muted small mb-4">Explora nuestro catálogo y empieza tu colección digital.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-cart3 me-1"></i> Ver Libros
                        </a>
                    </div>
                @else
                    <!-- Listado de Pedidos -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Nº Pedido</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Artículos</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Método</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="text-secondary">{{ $order->order_date }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info rounded-pill">{{ $order->orderItems->count() }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($order->isPaid())
                                                <span class="badge bg-success">Pagado</span>
                                            @elseif($order->payment_status === 'pending')
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                @switch($order->payment_method)
                                                    @case('credit_card')
                                                        💳 Tarjeta
                                                        @break
                                                    @case('rapipago')
                                                        💰 Rapipago
                                                        @break
                                                    @default
                                                        MP
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm shadow-hover">
                                                <i class="bi bi-eye me-1"></i> Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                Detalle del Pedido #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Volver al historial
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 ps-4">
                        <h5 class="mb-0 fw-bold">Libros Adquiridos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Libro</th>
                                        <th>Autor</th>
                                        <th class="text-end pe-4">Precio</th>
                                        <th class="text-center">Descarga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        @php
                                            // Guard for missing sellables
                                            if(!$item->sellable) continue;
                                            
                                            $isBook = $item->sellable_type === 'App\Models\Book';
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-bold">
                                                {{ $item->sellable->title }}
                                                @if(!$isBook)
                                                    <span class="badge bg-primary rounded-pill ms-2 small">Curso en Video</span>
                                                @endif
                                            </td>
                                            <td class="text-secondary">{{ $item->sellable->author->name ?? 'N/A' }}</td>
                                            <td class="text-end pe-4 fw-bold text-primary">
                                                ${{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="text-center">
                                                @if($isBook)
                                                    <!-- Enlace de descarga segura -->
                                                    <a href="{{ route('download.book', $item->sellable_id) }}" class="btn btn-success btn-sm rounded-circle shadow-sm" title="Descargar Libro">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @else
                                                    <!-- Enlace para ver el video -->
                                                    <a href="{{ route('courses.watch', $item->sellable_id) }}" class="btn btn-primary btn-sm rounded-circle shadow-sm" title="Ver Video">
                                                        <i class="bi bi-play-circle-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Resumen de Compra</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Impuestos:</span>
                            <span>$0.00</span>
                        </div>
                        <hr class="bg-white">
                        <div class="d-flex justify-content-between fs-4 fw-bold">
                            <span>Total:</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="mt-4 pt-2">
                            <div class="badge bg-white text-primary rounded-pill p-2 px-3 fw-bold w-100">
                                <i class="bi bi-check-circle-fill me-1"></i> Estado: {{ $order->status }}
                            </div>
                            <div class="badge bg-white text-secondary rounded-pill p-2 px-3 fw-bold w-100 mt-2">
                                Pago: {{ ucfirst($order->payment_status ?? 'desconocido') }}
                            </div>
                            <div class="badge bg-white text-secondary rounded-pill p-2 px-3 fw-bold w-100 mt-2">
                                Método: {{ ucfirst($order->payment_method ?? 'no definido') }}
                            </div>

                            @if(!$order->isPaid() && in_array($order->payment_status, ['pending', 'rejected', 'cancelled']))
                                <form method="POST" action="{{ route('orders.retryPayment', $order) }}" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm w-100 rounded-pill">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reintentar Pago
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
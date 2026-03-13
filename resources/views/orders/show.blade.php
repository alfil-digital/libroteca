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
                                        <tr>
                                            <td class="ps-4 fw-bold">{{ $item->book->title }}</td>
                                            <td class="text-secondary">{{ $item->book->author->name }}</td>
                                            <td class="text-end pe-4 fw-bold text-primary">
                                                ${{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <!-- Enlace de descarga segura -->
                                                <a href="{{ route('download.book', $item->book) }}" class="btn btn-success btn-sm rounded-circle shadow-sm" title="Descargar Libro">
                                                    <i class="bi bi-download"></i>
                                                </a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
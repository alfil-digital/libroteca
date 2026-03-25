<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Tu Carrito de Compras') }} <!-- Título de la página -->
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if(empty($items))
                    <!-- Estado vacío del carrito -->
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x text-muted display-1"></i>
                        <h4 class="mt-3 text-secondary">Tu carrito está vacío</h4>
                        <p class="text-muted small mb-4">Parece que aún no has añadido ningún producto interesante.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-search me-1"></i> Explorar Catálogo
                        </a>
                    </div>
                @else
                    <!-- Listado de artículos en el carrito -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-light">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Producto</th>
                                    <th>Autor</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-end pe-4">Precio</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $isBook = $item->sellable_type === 'App\Models\Book';
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">
                                            {{ $item->sellable->title }}
                                        </td>
                                        <td class="text-secondary">
                                            {{ $item->sellable->author->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            @if($isBook)
                                                <span class="badge bg-secondary rounded-pill small">Libro Digital</span>
                                            @else
                                                <span class="badge bg-primary rounded-pill small">Curso en Video</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-primary">
                                            ${{ number_format($item->sellable->price, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <!-- Botón para eliminar un ítem -->
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-pill shadow-sm" title="Quitar">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top-2 border-primary bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4">Total de la Compra:</td>
                                    <td class="text-end pe-4 fs-5 fw-bold text-success">${{ number_format($total, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <!-- Botón para vaciar el carrito -->
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger text-decoration-none small"
                                onclick="return confirm('¿Seguro que quieres vaciar el carrito?')">
                                <i class="bi bi-trash-fill me-1"></i> Vaciar Carrito
                            </button>
                        </form>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                                Seguir Comprando
                            </a>
                            
                            @auth
                                <form action="{{ route('orders.store') }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf

                                    <select name="payment_method" class="form-select form-select-sm w-auto">
                                        <option value="all">Todos los métodos</option>
                                        <option value="credit_card">Tarjeta de Crédito</option>
                                        <option value="rapipago">Rapipago/Pago en Efectivo</option>
                                    </select>

                                    <button type="submit"
                                        class="btn btn-success rounded-pill px-5 fw-bold shadow-sm shadow-hover">
                                        <i class="bi bi-credit-card me-2"></i> Finalizar Compra
                                    </button>
                                </form>
                            @else
                                <!-- Botón para invitado -->
                                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Identifícate para comprar
                                </a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
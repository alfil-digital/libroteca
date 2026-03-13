<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Tu Carrito de Compras') }} <!-- Título de la página -->
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if(!$cart || $cart->cartItems->isEmpty())
                    <!-- Estado vacío del carrito -->
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x text-muted display-1"></i>
                        <h4 class="mt-3 text-secondary">Tu carrito está vacío</h4>
                        <p class="text-muted small mb-4">Parece que aún no has añadido ningún libro interesante.</p>
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
                                    <th class="ps-4">Libro</th>
                                    <th>Autor</th>
                                    <th class="text-center">Formato</th>
                                    <th class="text-end pe-4">Precio</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->cartItems as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $item->book->title }}</td>
                                        <td class="text-secondary">{{ $item->book->author->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary rounded-pill small">{{ $item->book->format }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-primary">
                                            ${{ number_format($item->book->price, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <!-- Botón para eliminar un ítem -->
                                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
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
                            <a href="{{ route('books.index') }}"
                                class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                                Seguir Comprando
                            </a>
                            <!-- Botón para finalizar la compra -->
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-success rounded-pill px-5 fw-bold shadow-sm shadow-hover">
                                    <i class="bi bi-credit-card me-2"></i> Finalizar Compra
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
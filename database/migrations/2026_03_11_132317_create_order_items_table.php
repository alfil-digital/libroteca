<?php

use Illuminate\Database\Migrations\Migration; // Clase base
use Illuminate\Database\Schema\Blueprint; // Clase constructora
use Illuminate\Support\Facades\Schema; // Fachada

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de ítems de pedido.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // ID
            // Clave foránea hacia el pedido padre
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // Clave foránea hacia el libro en este pedido
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->decimal('unit_price', 10, 2); // Precio al momento de la compra
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items'); // Elimina la tabla
    }
};

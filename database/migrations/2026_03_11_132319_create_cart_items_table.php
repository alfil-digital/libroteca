<?php

use Illuminate\Database\Migrations\Migration; // Clase base
use Illuminate\Database\Schema\Blueprint; // Constructor
use Illuminate\Support\Facades\Schema; // Fachada

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de ítems de carrito.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id(); // ID
            // Clave foránea hacia el carrito de compras
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            // Clave foránea hacia el libro en el carrito
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items'); // Elimina la tabla
    }
};

<?php

use Illuminate\Database\Migrations\Migration; // Base de migración
use Illuminate\Database\Schema\Blueprint; // Herramienta de esquema
use Illuminate\Support\Facades\Schema; // Fachada de acceso

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de pedidos.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID
            // Clave foránea hacia los usuarios que compraron los artículos
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('order_date'); // Fecha en que se realizó el pedido
            $table->decimal('total_amount', 10, 2); // Precio total
            $table->string('status')->default('Pending'); // Estado del pedido
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders'); // Elimina la tabla
    }
};

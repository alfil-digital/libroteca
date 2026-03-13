<?php

use Illuminate\Database\Migrations\Migration; // Clase base
use Illuminate\Database\Schema\Blueprint; // Constructor de esquemas
use Illuminate\Support\Facades\Schema; // Fachada

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de carritos.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // ID
            // Clave foránea hacia el usuario dueño
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('last_activity')->useCurrent(); // Rastreo de actividad de sesión
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts'); // Elimina la tabla
    }
};

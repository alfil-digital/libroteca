<?php

use Illuminate\Database\Migrations\Migration; // Clase base
use Illuminate\Database\Schema\Blueprint; // Constructor de esquemas
use Illuminate\Support\Facades\Schema; // Fachada

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de categorías.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID
            $table->string('name')->unique(); // Nombre de la categoría (Género) único
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories'); // Elimina la tabla
    }
};

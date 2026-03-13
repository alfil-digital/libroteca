<?php

use Illuminate\Database\Migrations\Migration; // Base de migración
use Illuminate\Database\Schema\Blueprint; // Blueprint de tabla
use Illuminate\Support\Facades\Schema; // Fachada Schema

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de autores.
     */
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id(); // ID primario
            $table->string('name'); // Nombre completo del autor
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors'); // Elimina la tabla
    }
};

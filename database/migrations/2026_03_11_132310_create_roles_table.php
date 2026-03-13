<?php

use Illuminate\Database\Migrations\Migration; // Importa la clase base de migración
use Illuminate\Database\Schema\Blueprint; // Importa Blueprint para la definición de tablas
use Illuminate\Support\Facades\Schema; // Importa la fachada Schema

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de roles.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // ID autoincremental como clave primaria
            $table->string('name')->unique(); // Nombre único para el rol (ej., Admin)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('roles'); // Elimina la tabla roles si existe
    }
};

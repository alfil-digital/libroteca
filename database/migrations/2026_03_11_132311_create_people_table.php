<?php

use Illuminate\Database\Migrations\Migration; // Importa la clase de migración
use Illuminate\Database\Schema\Blueprint; // Importa la clase Blueprint
use Illuminate\Support\Facades\Schema; // Importa la fachada Schema

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de personas.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id(); // ID como clave primaria
            $table->string('first_name'); // Nombre de la persona
            $table->string('last_name'); // Apellido de la persona
            $table->string('id_number')->unique(); // Número de identificación/DNI, debe ser único
            $table->string('email')->unique(); // Correo personal, debe ser único
            $table->string('phone')->nullable(); // Número de teléfono opcional
            $table->text('address')->nullable(); // Dirección física opcional
            $table->timestamps(); // Marcas de tiempo estándar
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('people'); // Elimina la tabla al deshacer la migración
    }
};

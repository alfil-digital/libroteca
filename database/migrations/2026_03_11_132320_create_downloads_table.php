<?php

use Illuminate\Database\Migrations\Migration; // Clase base
use Illuminate\Database\Schema\Blueprint; // Constructor
use Illuminate\Support\Facades\Schema; // Fachada

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de logs de descargas.
     */
    public function up(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id(); // ID primario
            // Clave foránea hacia el usuario que descargó
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Clave foránea hacia el libro descargado
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamp('download_date')->useCurrent(); // Cuándo ocurrió
            $table->string('ip_address', 45)->nullable(); // Dirección IP del usuario
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads'); // Elimina la tabla
    }
};

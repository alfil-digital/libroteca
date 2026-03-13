<?php

use Illuminate\Database\Migrations\Migration; // Clase para migración
use Illuminate\Database\Schema\Blueprint; // Clase para esquemas
use Illuminate\Support\Facades\Schema; // Fachada para esquemas

return new class extends Migration {
    /**
     * Ejecuta las migraciones para crear la tabla de libros.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // ID primario
            $table->string('title'); // Título del libro
            $table->string('isbn')->unique(); // Código ISBN único
            $table->string('publisher')->nullable(); // Nombre de la editorial
            $table->year('publication_year')->nullable(); // Año de publicación
            $table->decimal('price', 8, 2); // Precio unitario de la copia digital
            $table->string('file_path'); // Ruta interna hacia el archivo digital
            $table->string('format'); // Formato de archivo (PDF, EPUB)
            $table->integer('file_size'); // Tamaño en KB
            // Clave foránea hacia la tabla de autores
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            // Clave foránea hacia la tabla de categorías
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Marcas de tiempo
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('books'); // Elimina la tabla
    }
};

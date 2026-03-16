<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El usuario que califica
            $table->unsignedBigInteger('rateable_id'); // ID del modelo calificado (libro, curso o autor)
            $table->string('rateable_type'); // Clase del modelo calificado
            $table->tinyInteger('stars')->default(5); // Puntuación de 1 a 5
            $table->text('comment')->nullable(); // Comentario opcional
            $table->timestamps();

            // Índice para mejorar búsquedas polimórficas
            $table->index(['rateable_id', 'rateable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};

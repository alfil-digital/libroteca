<?php

use Illuminate\Database\Migrations\Migration; // Importa la clase de migración
use Illuminate\Database\Schema\Blueprint; // Importa la clase Blueprint
use Illuminate\Support\Facades\Schema; // Importa la fachada Schema

return new class extends Migration {
    /**
     * Ejecuta las migraciones para añadir claves foráneas a la tabla de usuarios.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Añade la columna person_id después de ID, opcional, vinculada a la tabla people
            $table->foreignId('person_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            // Añade la columna role_id después de person_id, opcional, vinculada a la tabla roles
            $table->foreignId('role_id')->after('person_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Revierte las migraciones (elimina las columnas).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']); // Elimina la clave foránea person_id
            $table->dropColumn('person_id'); // Elimina la columna person_id
            $table->dropForeign(['role_id']); // Elimina la clave foránea role_id
            $table->dropColumn('role_id'); // Elimina la columna role_id
        });
    }
};

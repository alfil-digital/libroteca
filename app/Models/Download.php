<?php

namespace App\Models; // Espacio de nombres para modelos

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para factorías
use Illuminate\Database\Eloquent\Model; // Clase base de Eloquent

class Download extends Model
{
    use HasFactory; // Permite el uso de factorías

    /**
     * Atributos que son asignables de forma masiva.
     */
    protected $fillable = [
        'user_id',       // El usuario que realizó la descarga
        'book_id',       // El libro digital que se está descargando
        'download_date', // Marca de tiempo exacta de la descarga
        'ip_address',    // Dirección IP del usuario para seguridad/rastreo
    ];

    /**
     * Relación con el modelo User (muchos a uno).
     */
    public function user()
    {
        return $this->belongsTo(User::class); // La descarga está vinculada a un usuario
    }

    /**
     * Relación con el modelo Book (muchos a uno).
     */
    public function book()
    {
        return $this->belongsTo(Book::class); // La descarga se refiere a un libro específico
    }
}

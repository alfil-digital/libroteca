<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * Atributos que pueden ser llenados masivamente.
     */
    protected $fillable = [
        'user_id',
        'rateable_id',
        'rateable_type',
        'stars',
        'comment',
    ];

    /**
     * Relación polimórfica: puede pertenecer a un Libro, Curso o Autor.
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * Relación con el usuario que realizó la valoración.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

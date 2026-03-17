<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Book;
use App\Models\Course;
use App\Models\Author;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Almacena una nueva valoración para un producto o autor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rateable_id' => 'required|integer',
            'rateable_type' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $rateableId = $request->rateable_id;
        $rateableType = $request->rateable_type;

        // Validar si el objeto existe
        $model = $rateableType::findOrFail($rateableId);

        // Si es un libro o curso, validar que el usuario lo haya comprado
        if ($rateableType === Book::class) {
            if (!$user->hasPurchasedBook($model)) {
                return back()->with('error', 'Debes comprar este libro antes de poder valorarlo.');
            }
        }

        if ($rateableType === Course::class) {
            if (!$user->hasPurchasedCourse($model)) {
                return back()->with('error', 'Debes comprar este curso antes de poder valorarlo.');
            }
        }

        if ($rateableType === Author::class) {
            if (!$user->hasPurchasedFromAuthor($model)) {
                return back()->with('error', 'Debes haber adquirido al menos un producto de este autor para poder valorarlo.');
            }
        }

        // Comprobar si ya lo valoró anteriormente (para evitar duplicados del mismo usuario en el mismo item)
        $existing = Rating::where('user_id', $user->id)
            ->where('rateable_id', $rateableId)
            ->where('rateable_type', $rateableType)
            ->first();

        if ($existing) {
            $existing->update([
                'stars' => $request->stars,
                'comment' => $request->comment,
            ]);
            $message = 'Tu valoración ha sido actualizada.';
        } else {
            Rating::create([
                'user_id' => $user->id,
                'rateable_id' => $rateableId,
                'rateable_type' => $rateableType,
                'stars' => $request->stars,
                'comment' => $request->comment,
            ]);
            $message = '¡Gracias por tu valoración!';
        }

        return back()->with('success', $message);
    }
}

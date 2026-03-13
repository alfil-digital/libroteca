<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Download;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Procesa la descarga segura de un libro.
     */
    public function download(Book $book)
    {
        // 1. Verificar que el usuario haya comprado este libro (esté en un pedido 'Completed')
        $hasPurchased = OrderItem::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('status', 'Completed');
        })
            ->where('book_id', $book->id)
            ->exists();

        // Si no lo compró, denegar
        if (!$hasPurchased) {
            return redirect()->route('books.index')->with('error', 'Debes comprar este libro para descargarlo.');
        }

        // 2. Verificar que el archivo exista en el disco 'public'
        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            return redirect()->back()->with('error', 'El archivo no está disponible actualmente.');
        }

        // 3. Registrar el log de descarga
        Download::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'download_date' => now(),
            'ip_address' => request()->ip(),
        ]);

        // 4. Iniciar la descarga del archivo de forma segura
        return Storage::disk('public')->download(
            $book->file_path,
            $book->title . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION)
        );
    }
}

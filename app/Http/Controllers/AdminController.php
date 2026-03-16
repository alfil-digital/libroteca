<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Muestra el panel principal de administración con estadísticas.
     */
    public function dashboard()
    {
        // Verificar si el usuario es administrador (Aunque ya debería estar protegido por middleware)
        if (!auth()->user()->hasRole('administrador')) {
            return redirect()->route('dashboard');
        }

        $stats = [
            'users' => User::count(),
            'books' => Book::count(),
            'courses' => Course::count(),
            'authors' => Author::count(),
            'orders' => Order::where('status', 'Completed')->count(),
            'revenue' => Order::where('status', 'Completed')->sum('total_amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

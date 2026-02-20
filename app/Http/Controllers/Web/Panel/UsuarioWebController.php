<?php

namespace App\Http\Controllers\Web\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UsuarioWebController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', User::class);

        $usuarios = User::query()
            ->when($request->search, fn($q) => $q
                ->where('name', 'ilike', "%{$request->search}%")
                ->orWhere('email', 'ilike', "%{$request->search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('panel/usuarios/Index', [
            'usuarios' => $usuarios,
            'filtros'  => $request->only('search'),
        ]);
    }
}

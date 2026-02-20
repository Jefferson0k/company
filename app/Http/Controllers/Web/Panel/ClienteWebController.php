<?php

namespace App\Http\Controllers\Web\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ClienteWebController extends Controller
{
    public function __construct(
        private readonly ClienteService $clienteService,
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Cliente::class);

        return Inertia::render('panel/clientes/Index', [
            'clientes' => $this->clienteService->listar($request->only([
                'search', 'tipo_persona', 'estado',
            ])),
            'filtros' => $request->only(['search', 'tipo_persona', 'estado']),
        ]);
    }

    public function show(Cliente $cliente): Response
    {
        Gate::authorize('view', $cliente);

        $cliente->load([
            'boletas' => fn($q) => $q->orderBy('created_at', 'desc'),
            'puntos',
        ]);

        return Inertia::render('panel/clientes/Show', [
            'cliente'     => $cliente,
            'totalPuntos' => $cliente->totalPuntos(),
        ]);
    }
}

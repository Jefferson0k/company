<?php

namespace App\Http\Controllers\Web\Panel;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Services\BoletaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BoletaWebController extends Controller
{
    public function __construct(
        private readonly BoletaService $boletaService,
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Boleta::class);

        return Inertia::render('panel/boletas/Index', [
            'boletas' => $this->boletaService->listar($request->only([
                'search', 'estado', 'cliente_id',
            ])),
            'filtros' => $request->only(['search', 'estado', 'cliente_id']),
        ]);
    }
}

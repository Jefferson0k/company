<?php

namespace App\Http\Controllers\Web\Panel;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Cliente;
use Inertia\Inertia;
use Inertia\Response;

class DashboardWebController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('panel/Dashboard', [
            'stats' => [
                'total_clientes'      => Cliente::count(),
                'clientes_activos'    => Cliente::where('estado', 'activo')->count(),
                'clientes_pendientes' => Cliente::where('estado', 'pendiente')->count(),
                'boletas_pendientes'  => Boleta::where('estado', 'pendiente')->count(),
                'boletas_aceptadas'   => Boleta::where('estado', 'aceptada')->count(),
                'boletas_rechazadas'  => Boleta::where('estado', 'rechazada')->count(),
            ],
        ]);
    }
}

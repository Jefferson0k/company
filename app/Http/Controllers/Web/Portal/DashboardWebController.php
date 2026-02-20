<?php

namespace App\Http\Controllers\Web\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardWebController extends Controller
{
    public function index(): Response
    {
        $cliente = Auth::guard('cliente')->user();

        return Inertia::render('portal/Dashboard', [
            'cliente'     => $cliente,
            'boletas'     => $cliente->boletas()
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'totalPuntos' => $cliente->totalPuntos(),
        ]);
    }
}

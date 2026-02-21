<?php

namespace App\Http\Controllers\Web\Portal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PoliticasPrivacidadWebController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('portal/politicas-privacidad/indexPoliticasPrivacidad');
    }
}

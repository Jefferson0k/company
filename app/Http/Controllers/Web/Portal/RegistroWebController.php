<?php

namespace App\Http\Controllers\Web\Portal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class RegistroWebController extends Controller
{
    public function registro(): Response
    {
        return Inertia::render('portal/registro/indexRegistro');
    }
}

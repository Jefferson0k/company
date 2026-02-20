<?php

namespace App\Http\Controllers\Web\Portal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class RegistroWebController extends Controller
{
    public function natural(): Response
    {
        return Inertia::render('portal/registro/Natural');
    }

    public function juridica(): Response
    {
        return Inertia::render('portal/registro/Juridica');
    }
}

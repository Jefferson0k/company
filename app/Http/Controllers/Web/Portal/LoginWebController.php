<?php

namespace App\Http\Controllers\Web\Portal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LoginWebController extends Controller
{
    public function login(): Response
    {
        return Inertia::render('portal/auth/login/indexLogin');
    }
}

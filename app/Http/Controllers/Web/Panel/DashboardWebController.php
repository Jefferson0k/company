<?php

namespace App\Http\Controllers\Web\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardWebController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('portal/dashboard/indexDashboard');
    }
}

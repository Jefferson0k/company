<?php

use App\Http\Controllers\Api\Portal\ClienteAuthController;
use App\Http\Controllers\Web\Panel\DashboardWebController;
use App\Http\Controllers\Web\Portal\LoginWebController;
use App\Http\Controllers\Web\Portal\PoliticasPrivacidadWebController;
use App\Http\Controllers\Web\Portal\RegistroWebController;
use App\Http\Controllers\Web\Portal\TerminiosCondicionesWebController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Spatie\Honeypot\ProtectAgainstSpam;

// ─── Página principal ─────────────────────────────────────────────────────────
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// ─── Panel admin/trabajadores ─────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
});

// ─── Portal cliente — público ─────────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {

    // Páginas informativas
    Route::get('/terminos-condiciones', [TerminiosCondicionesWebController::class, 'index'])
        ->name('terminos-condiciones');

    Route::get('/politicas-privacidad', [PoliticasPrivacidadWebController::class, 'index'])
        ->name('politicas-privacidad');

    // Autenticación cliente
    Route::get('/registro', [RegistroWebController::class, 'registro'])->name('registro');
    Route::get('/login',    [LoginWebController::class, 'login'])->name('login');

    // POST registro con protección anti-bots y rate limiting
    Route::middleware(['throttle:registro', ProtectAgainstSpam::class])->group(function () {
        Route::post('/registro', [ClienteAuthController::class, 'register'])->name('registro.store');
    });

    // POST login con rate limiting
    Route::middleware(['throttle:login.cliente'])->group(function () {
        Route::post('/login', [ClienteAuthController::class, 'login'])->name('login.store');
    });

});

// ─── Portal cliente — autenticado ─────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->middleware(['auth:cliente'])->group(function () {

    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');

});

require __DIR__.'/settings.php';
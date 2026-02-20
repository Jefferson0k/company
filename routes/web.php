<?php

use App\Http\Controllers\Web\Portal\RegistroWebController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::prefix('portal')->group(function () {
    Route::get('registro/natural', [RegistroWebController::class, 'natural'])->name('registro.natural');
    Route::get('registro/juridica', [RegistroWebController::class, 'juridica'])->name('registro.juridica');
});
require __DIR__.'/settings.php';
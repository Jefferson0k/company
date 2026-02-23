<?php

use App\Http\Middleware\ClienteEmailVerificado;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\TokenFromCookie;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__ . '/../routes/web.php',
        api:      __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ─────────────────────────────────────────────────────────
        // Cookies encriptadas (excepciones para cookies de UI)
        // ─────────────────────────────────────────────────────────
        $middleware->encryptCookies(except: [
            'appearance',
            'sidebar_state',
        ]);

        // ─────────────────────────────────────────────────────────
        // CSRF: excluir todas las rutas API
        // No se necesita CSRF porque usamos Bearer token extraído
        // de la cookie HttpOnly mediante TokenFromCookie
        // ─────────────────────────────────────────────────────────
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        // ─────────────────────────────────────────────────────────
        // Aliases de middleware personalizados
        // ─────────────────────────────────────────────────────────
        $middleware->alias([
            'cliente.verified' => ClienteEmailVerificado::class,
            'auth.cliente'     => \App\Http\Middleware\AuthenticateCliente::class,
        ]);

        // ─────────────────────────────────────────────────────────
        // Middleware del grupo WEB
        // ─────────────────────────────────────────────────────────
        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // ─────────────────────────────────────────────────────────
        // Middleware del grupo API
        // TokenFromCookie lee la cookie auth_token (ya desencriptada)
        // y la inyecta como Bearer token en el header Authorization
        // ─────────────────────────────────────────────────────────
        $middleware->api(prepend: [
            TokenFromCookie::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
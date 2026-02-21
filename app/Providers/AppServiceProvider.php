<?php

namespace App\Providers;

use App\Models\Boleta;
use App\Models\Cliente;
use App\Models\Notificacion;
use App\Models\Punto;
use App\Models\User;
use App\Policies\BoletaPolicy;
use App\Policies\ClientePolicy;
use App\Policies\NotificacionPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PuntoPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use PDOException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();

        try {
            DB::connection()->getPdo();

            if (Schema::hasTable('permissions') && Schema::hasTable('roles')) {
                Gate::policy(Cliente::class,    ClientePolicy::class);
                Gate::policy(Boleta::class,     BoletaPolicy::class);
                Gate::policy(Punto::class,      PuntoPolicy::class);
                Gate::policy(Notificacion::class, NotificacionPolicy::class);
                Gate::policy(User::class,       UserPolicy::class);
                Gate::policy(Permission::class, PermissionPolicy::class);
                Gate::policy(Role::class,       RolePolicy::class);
            }
        } catch (PDOException $e) {
            // Silencioso — evita romper artisan commands cuando no hay BD disponible
        }
    }

    /**
     * Configuraciones globales de la aplicación.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    /**
     * Configuración de rate limiting — siempre corre, independiente de la BD.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('registro', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perHour(10)->by($request->ip()),
                Limit::perDay(20)->by($request->ip()),
            ];
        });

        RateLimiter::for('login.cliente', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perMinute(5)->by($request->input('email')),
            ];
        });
    }
}
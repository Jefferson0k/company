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
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
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
         try {
            DB::connection()->getPdo();
            if (Schema::hasTable('permissions') && Schema::hasTable('roles')) {
                    Gate::policy(Cliente::class, ClientePolicy::class);
                    Gate::policy(Boleta::class, BoletaPolicy::class);
                    Gate::policy(Punto::class, PuntoPolicy::class);
                    Gate::policy(Notificacion::class, NotificacionPolicy::class);
                    Gate::policy(User::class, UserPolicy::class);
                    Gate::policy(Permission::class, PermissionPolicy::class);
                    Gate::policy(Role::class, RolePolicy::class);
            }
        } catch (PDOException $e) {
            
        }
    }

    /**
     * Configure default behaviors for production-ready applications.
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
}

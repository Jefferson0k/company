<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);

        // Clientes
        Permission::create(['name' => 'ver clientes']);
        Permission::create(['name' => 'crear clientes']);
        Permission::create(['name' => 'editar clientes']);
        Permission::create(['name' => 'eliminar clientes']);

        // Boletas
        Permission::create(['name' => 'ver boletas']);
        Permission::create(['name' => 'crear boletas']);
        Permission::create(['name' => 'editar boletas']);
        Permission::create(['name' => 'eliminar boletas']);

        // Puntos
        Permission::create(['name' => 'ver puntos']);
        Permission::create(['name' => 'crear puntos']);
        Permission::create(['name' => 'editar puntos']);
        Permission::create(['name' => 'eliminar puntos']);

        // Notificaciones
        Permission::create(['name' => 'ver notificaciones']);
        Permission::create(['name' => 'crear notificaciones']);
        Permission::create(['name' => 'editar notificaciones']);
        Permission::create(['name' => 'eliminar notificaciones']);

        // Roles
        Permission::create(['name' => 'ver roles']);
        Permission::create(['name' => 'crear roles']);
        Permission::create(['name' => 'editar roles']);
        Permission::create(['name' => 'eliminar roles']);

        // Permisos
        Permission::create(['name' => 'ver permisos']);
        Permission::create(['name' => 'crear permisos']);
        Permission::create(['name' => 'editar permisos']);
        Permission::create(['name' => 'eliminar permisos']);

        // Reportes
        Permission::create(['name' => 'ver reportes']);
    }
}

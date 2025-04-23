<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear rol de administrador
        $admin = Role::create([
            'name' => 'admin',
            'description' => 'Administrador del sistema',
            'guard_name' => 'web'
        ]);
        
        // Asignar todos los permisos al rol Administrador
        $admin->givePermissionTo(Permission::all());

        // Crear rol de inventario
        $inventario = Role::create([
            'name' => 'inventario',
            'description' => 'Usuario de inventario',
            'guard_name' => 'web'
        ]);

        // Asignar permisos de inventario
        $inventario->givePermissionTo([
            'ver-materias-primas', 'crear-materias-primas', 'editar-materias-primas', 'eliminar-materias-primas',
            'ver-productos', 'crear-productos', 'editar-productos', 'eliminar-productos',
            'ver-inventario', 'crear-inventario', 'editar-inventario', 'eliminar-inventario',
            'ver-reportes'
        ]);

        // Crear rol de ventas
        $ventas = Role::create([
            'name' => 'ventas',
            'description' => 'Usuario de ventas',
            'guard_name' => 'web'
        ]);

        // Asignar permisos de ventas
        $ventas->givePermissionTo([
            'ver-productos',
            'ver-ventas', 'crear-ventas', 'editar-ventas', 'eliminar-ventas',
            'ver-clientes', 'crear-clientes', 'editar-clientes', 'eliminar-clientes',
            'ver-reportes'
        ]);

        // Crear rol de producción
        $produccion = Role::create([
            'name' => 'produccion',
            'description' => 'Usuario de producción',
            'guard_name' => 'web'
        ]);

        // Asignar permisos de producción
        $produccion->givePermissionTo([
            'ver-materias-primas',
            'ver-productos', 'crear-productos', 'editar-productos',
            'ver-inventario',
            'ver-produccion', 'crear-produccion', 'editar-produccion',
            'ver-reportes'
        ]);
    }
} 
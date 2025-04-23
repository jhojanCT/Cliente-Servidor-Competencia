<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Eliminar todos los permisos existentes
        Permission::query()->delete();

        // Permisos para Usuarios
        Permission::create([
            'name' => 'ver-usuarios',
            'description' => 'Ver lista de usuarios',
            'group' => 'usuarios',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-usuarios',
            'description' => 'Crear nuevos usuarios',
            'group' => 'usuarios',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-usuarios',
            'description' => 'Editar usuarios existentes',
            'group' => 'usuarios',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-usuarios',
            'description' => 'Eliminar usuarios',
            'group' => 'usuarios',
            'guard_name' => 'web'
        ]);

        // Permisos para Roles
        Permission::create([
            'name' => 'ver-roles',
            'description' => 'Ver lista de roles',
            'group' => 'roles',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-roles',
            'description' => 'Crear nuevos roles',
            'group' => 'roles',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-roles',
            'description' => 'Editar roles existentes',
            'group' => 'roles',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-roles',
            'description' => 'Eliminar roles',
            'group' => 'roles',
            'guard_name' => 'web'
        ]);

        // Permisos para Permisos
        Permission::create([
            'name' => 'ver-permisos',
            'description' => 'Ver lista de permisos',
            'group' => 'permisos',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-permisos',
            'description' => 'Crear nuevos permisos',
            'group' => 'permisos',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-permisos',
            'description' => 'Editar permisos existentes',
            'group' => 'permisos',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-permisos',
            'description' => 'Eliminar permisos',
            'group' => 'permisos',
            'guard_name' => 'web'
        ]);

        // Permisos para Cuentas Bancarias
        Permission::create([
            'name' => 'ver-cuentas-bancarias',
            'description' => 'Ver lista de cuentas bancarias',
            'group' => 'cuentas-bancarias',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-cuentas-bancarias',
            'description' => 'Crear nuevas cuentas bancarias',
            'group' => 'cuentas-bancarias',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-cuentas-bancarias',
            'description' => 'Editar cuentas bancarias existentes',
            'group' => 'cuentas-bancarias',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-cuentas-bancarias',
            'description' => 'Eliminar cuentas bancarias',
            'group' => 'cuentas-bancarias',
            'guard_name' => 'web'
        ]);

        // Permisos para Movimientos Bancarios
        Permission::create([
            'name' => 'ver-movimientos-bancarios',
            'description' => 'Ver lista de movimientos bancarios',
            'group' => 'movimientos-bancarios',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-movimientos-bancarios',
            'description' => 'Crear nuevos movimientos bancarios',
            'group' => 'movimientos-bancarios',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-movimientos-bancarios',
            'description' => 'Editar movimientos bancarios existentes',
            'group' => 'movimientos-bancarios',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-movimientos-bancarios',
            'description' => 'Eliminar movimientos bancarios',
            'group' => 'movimientos-bancarios',
            'guard_name' => 'web'
        ]);

        // Permisos para Proveedores
        Permission::create([
            'name' => 'ver-proveedores',
            'description' => 'Ver lista de proveedores',
            'group' => 'proveedores',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-proveedores',
            'description' => 'Crear nuevos proveedores',
            'group' => 'proveedores',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-proveedores',
            'description' => 'Editar proveedores existentes',
            'group' => 'proveedores',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-proveedores',
            'description' => 'Eliminar proveedores',
            'group' => 'proveedores',
            'guard_name' => 'web'
        ]);

        // Permisos para Materias Primas
        Permission::create([
            'name' => 'ver-materias-primas',
            'description' => 'Ver lista de materias primas',
            'group' => 'materias-primas',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-materias-primas',
            'description' => 'Crear nuevas materias primas',
            'group' => 'materias-primas',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-materias-primas',
            'description' => 'Editar materias primas existentes',
            'group' => 'materias-primas',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-materias-primas',
            'description' => 'Eliminar materias primas',
            'group' => 'materias-primas',
            'guard_name' => 'web'
        ]);

        // Permisos para Productos
        Permission::create([
            'name' => 'ver-productos',
            'description' => 'Ver lista de productos',
            'group' => 'productos',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-productos',
            'description' => 'Crear nuevos productos',
            'group' => 'productos',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-productos',
            'description' => 'Editar productos existentes',
            'group' => 'productos',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-productos',
            'description' => 'Eliminar productos',
            'group' => 'productos',
            'guard_name' => 'web'
        ]);

        // Permisos para Inventario
        Permission::create([
            'name' => 'ver-inventario',
            'description' => 'Ver inventario',
            'group' => 'inventario',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-inventario',
            'description' => 'Crear registros de inventario',
            'group' => 'inventario',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-inventario',
            'description' => 'Editar registros de inventario',
            'group' => 'inventario',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-inventario',
            'description' => 'Eliminar registros de inventario',
            'group' => 'inventario',
            'guard_name' => 'web'
        ]);

        // Permisos para Compras
        Permission::create([
            'name' => 'ver-compras',
            'description' => 'Ver lista de compras',
            'group' => 'compras',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-compras',
            'description' => 'Crear nuevas compras',
            'group' => 'compras',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-compras',
            'description' => 'Editar compras existentes',
            'group' => 'compras',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-compras',
            'description' => 'Eliminar compras',
            'group' => 'compras',
            'guard_name' => 'web'
        ]);

        // Permisos para Ventas
        Permission::create([
            'name' => 'ver-ventas',
            'description' => 'Ver lista de ventas',
            'group' => 'ventas',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'crear-ventas',
            'description' => 'Crear nuevas ventas',
            'group' => 'ventas',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'editar-ventas',
            'description' => 'Editar ventas existentes',
            'group' => 'ventas',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'eliminar-ventas',
            'description' => 'Eliminar ventas',
            'group' => 'ventas',
            'guard_name' => 'web'
        ]);

        // Permisos para Reportes
        Permission::create([
            'name' => 'ver-reportes',
            'description' => 'Ver reportes',
            'group' => 'reportes',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'name' => 'generar-reportes',
            'description' => 'Generar reportes',
            'group' => 'reportes',
            'guard_name' => 'web'
        ]);

        // Permisos de Clientes
        Permission::create(['name' => 'ver-clientes', 'description' => 'Ver lista de clientes', 'group' => 'clientes', 'guard_name' => 'web']);
        Permission::create(['name' => 'crear-clientes', 'description' => 'Crear nuevos clientes', 'group' => 'clientes', 'guard_name' => 'web']);
        Permission::create(['name' => 'editar-clientes', 'description' => 'Editar clientes existentes', 'group' => 'clientes', 'guard_name' => 'web']);
        Permission::create(['name' => 'eliminar-clientes', 'description' => 'Eliminar clientes', 'group' => 'clientes', 'guard_name' => 'web']);

        // Permisos de Filtrado
        Permission::create(['name' => 'ver-filtrado', 'description' => 'Ver lista de filtrados', 'group' => 'filtrado', 'guard_name' => 'web']);
        Permission::create(['name' => 'crear-filtrado', 'description' => 'Crear nuevos filtrados', 'group' => 'filtrado', 'guard_name' => 'web']);
        Permission::create(['name' => 'editar-filtrado', 'description' => 'Editar filtrados existentes', 'group' => 'filtrado', 'guard_name' => 'web']);
        Permission::create(['name' => 'eliminar-filtrado', 'description' => 'Eliminar filtrados', 'group' => 'filtrado', 'guard_name' => 'web']);

        // Permisos de Pagos
        Permission::create(['name' => 'ver-pagos', 'description' => 'Ver lista de pagos', 'group' => 'pagos', 'guard_name' => 'web']);
        Permission::create(['name' => 'crear-pagos', 'description' => 'Crear nuevos pagos', 'group' => 'pagos', 'guard_name' => 'web']);
        Permission::create(['name' => 'editar-pagos', 'description' => 'Editar pagos existentes', 'group' => 'pagos', 'guard_name' => 'web']);
        Permission::create(['name' => 'eliminar-pagos', 'description' => 'Eliminar pagos', 'group' => 'pagos', 'guard_name' => 'web']);

        // Permisos de Producción
        Permission::create(['name' => 'ver-produccion', 'description' => 'Ver lista de producción', 'group' => 'produccion', 'guard_name' => 'web']);
        Permission::create(['name' => 'crear-produccion', 'description' => 'Crear nueva producción', 'group' => 'produccion', 'guard_name' => 'web']);
        Permission::create(['name' => 'editar-produccion', 'description' => 'Editar producción existente', 'group' => 'produccion', 'guard_name' => 'web']);
        Permission::create(['name' => 'eliminar-produccion', 'description' => 'Eliminar producción', 'group' => 'produccion', 'guard_name' => 'web']);
    }
} 
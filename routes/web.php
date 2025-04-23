<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CierreDiarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\CuentaBancariaController;
use App\Http\Controllers\FiltradoController;
use App\Http\Controllers\MateriaPrimaFiltradaController;
use App\Http\Controllers\MateriaPrimaSinFiltrarController;
use App\Http\Controllers\MovimientoBancarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Ruta pública de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cierre Diario
    Route::prefix('cierre')->group(function () {
        Route::get('/', [CierreDiarioController::class, 'index'])->name('cierre.index');
        Route::get('/create', [CierreDiarioController::class, 'create'])->name('cierre.create');
        Route::post('/', [CierreDiarioController::class, 'store'])->name('cierre.store');
        Route::get('/{id}', [CierreDiarioController::class, 'show'])->name('cierre.show');
        Route::get('/reporte/flujo-caja', [CierreDiarioController::class, 'reporteFlujoCaja'])->name('reporte.flujo-caja');
    });

    // Clientes
    Route::middleware(['can:ver-clientes'])->group(function () {
        Route::resource('clientes', ClienteController::class);
    });

    // Compras
    Route::middleware(['can:ver-compras'])->group(function () {
        Route::resource('compras', CompraController::class);
    });

    // Cuentas Bancarias
    Route::middleware(['can:ver-cuentas-bancarias'])->group(function () {
        Route::resource('cuentas-bancarias', CuentaBancariaController::class)->parameters([
            'cuentas-bancarias' => 'cuenta'
        ]);
    });

    // Movimientos Bancarios
    Route::middleware(['can:ver-movimientos-bancarios'])->group(function () {
        Route::prefix('cuentas-bancarias/{cuenta}')->group(function () {
            Route::get('/movimientos/create', [MovimientoBancarioController::class, 'create'])->name('movimientos-bancarios.create');
            Route::post('/movimientos', [MovimientoBancarioController::class, 'store'])->name('movimientos-bancarios.store');
        });
    });

    // Filtrado
    Route::middleware(['can:ver-filtrado'])->group(function () {
        Route::prefix('filtrado')->group(function () {
            Route::get('/', [FiltradoController::class, 'index'])->name('filtrado.index');
            Route::get('/create', [FiltradoController::class, 'create'])->name('filtrado.create');
            Route::post('/', [FiltradoController::class, 'store'])->name('filtrado.store');
        });
    });

    // Rutas para el módulo de filtrado
    Route::resource('filtrado', FiltradoController::class);

    // Materias Primas
    Route::middleware(['can:ver-materias-primas'])->group(function () {
        Route::resource('materia-prima-sin-filtrar', MateriaPrimaSinFiltrarController::class)->parameters([
            'materia-prima-sin-filtrar' => 'materia_prima_sin_filtrar'
        ]);
        Route::resource('materia-prima-filtrada', MateriaPrimaFiltradaController::class)->only(['index', 'show']);
    });

    // Pagos
    Route::middleware(['can:ver-pagos'])->group(function () {
        Route::prefix('pagos')->group(function () {
            Route::prefix('clientes')->group(function () {
                Route::get('/', [PagoController::class, 'indexClientes'])->name('pagos.clientes.index');
                Route::get('/create/{venta}', [PagoController::class, 'createCliente'])->name('pagos.clientes.create');
                Route::post('/store/{venta}', [PagoController::class, 'storeCliente'])->name('pagos.clientes.store');
            });

            Route::prefix('proveedores')->group(function () {
                Route::get('/', [PagoController::class, 'indexProveedores'])->name('pagos.proveedores.index');
                Route::get('/create/{compra}', [PagoController::class, 'createProveedor'])->name('pagos.proveedores.create');
                Route::post('/store/{compra}', [PagoController::class, 'storeProveedor'])->name('pagos.proveedores.store');
            });
        });
    });

    // Producción
    Route::middleware(['can:ver-produccion'])->group(function () {
        Route::resource('produccion', ProduccionController::class);
    });

    // Productos
    Route::middleware(['can:ver-productos'])->group(function () {
        Route::resource('productos', ProductoController::class);
    });

    // Proveedores
    Route::middleware(['can:ver-proveedores'])->group(function () {
        Route::resource('proveedores', ProveedorController::class);
    });
    
    // Reportes
    Route::middleware(['can:ver-reportes'])->group(function () {
        Route::prefix('reportes')->group(function () {
            Route::get('/inventario', [ReporteController::class, 'inventario'])->name('reportes.inventario');
            Route::get('/desperdicio', [ReporteController::class, 'desperdicio'])->name('reportes.desperdicio');
            Route::get('/produccion', [ReporteController::class, 'produccion'])->name('reportes.produccion');
            Route::get('/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
            Route::get('/compras', [ReporteController::class, 'compras'])->name('reportes.compras');
            Route::get('/flujo-caja', [ReporteController::class, 'flujoCaja'])->name('reportes.flujo-caja');
        });
    });

    // Ventas
    Route::middleware(['can:ver-ventas'])->group(function () {
        Route::resource('ventas', VentaController::class);
    });

    // Rutas de roles y permisos
    Route::middleware(['can:ver-roles'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Rutas de Permisos
    Route::middleware(['can:ver-permisos'])->group(function () {
        Route::resource('permissions', PermissionController::class);
    });

    // Usuarios
    Route::middleware(['can:ver-usuarios'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

// Rutas de autenticación (Breeze)
require __DIR__.'/auth.php';

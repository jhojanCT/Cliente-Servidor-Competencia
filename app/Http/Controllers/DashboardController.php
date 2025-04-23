<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener datos para las tarjetas
        $ventasTotales = Venta::sum('total');
        $productosStock = Producto::sum('stock');
        $clientesActivos = Cliente::count();

        // Obtener datos para el gráfico de ventas
        $ventasMensuales = Venta::selectRaw('MONTH(fecha) as mes, SUM(total) as total')
            ->whereYear('fecha', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return view('dashboard', compact(
            'ventasTotales',
            'productosStock',
            'clientesActivos',
            'ventasMensuales'
        ));
    }
}
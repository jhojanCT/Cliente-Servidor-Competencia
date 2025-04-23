<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrimaSinFiltrar;
use App\Models\MateriaPrimaFiltrada;
use App\Models\Producto;
use App\Models\Filtrado;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Produccion;
use App\Models\CierreDiario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function inventario()
    {
        $materiasPrimasSinFiltrar = MateriaPrimaSinFiltrar::with('compras')->get();
        $materiasPrimasFiltradas = MateriaPrimaFiltrada::all(); // Eliminado with('filtrados')
        $productos = Producto::with(['producciones', 'compras'])->get();
        
        return view('reportes.inventario', compact(
            'materiasPrimasSinFiltrar',
            'materiasPrimasFiltradas',
            'productos'
        ));
    }

    public function desperdicio(Request $request)
    {
        $query = Filtrado::with('materiaPrimaSinFiltrar');
        
        if ($request->fecha_inicio) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        $filtrados = $query->orderBy('fecha', 'desc')->get();
        
        $totalEntrada = $filtrados->sum('cantidad_entrada');
        $totalSalida = $filtrados->sum('cantidad_salida');
        $totalDesperdicio = $totalEntrada - $totalSalida;
        $porcentajeDesperdicio = $totalEntrada > 0 ? ($totalDesperdicio / $totalEntrada) * 100 : 0;
        
        return view('reportes.desperdicio', compact(
            'filtrados',
            'totalEntrada',
            'totalSalida',
            'totalDesperdicio',
            'porcentajeDesperdicio'
        ));
    }

    public function produccion(Request $request)
    {
        $query = Produccion::with(['materiaPrimaFiltrada', 'producto']);
        
        if ($request->fecha_inicio) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        if ($request->producto_id) {
            $query->where('producto_id', $request->producto_id);
        }
        
        $producciones = $query->orderBy('fecha', 'desc')->get();
        
        $totalMPUtilizada = $producciones->sum('cantidad_utilizada');
        $totalProductos = $producciones->sum('cantidad_producida');
        $totalCosto = $producciones->sum('costo_produccion');
        $costoPromedio = $totalProductos > 0 ? $totalCosto / $totalProductos : 0;
        
        $productos = Producto::where('tipo', 'producido')->get();
        
        return view('reportes.produccion', compact(
            'producciones',
            'totalMPUtilizada',
            'totalProductos',
            'totalCosto',
            'costoPromedio',
            'productos'
        ));
    }

    public function ventas(Request $request)
    {
        $query = Venta::with(['cliente', 'items.producto']);
        
        if ($request->fecha_inicio) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        $ventas = $query->orderBy('fecha', 'desc')->get();
        
        $totalVentas = $ventas->sum('total');
        $ventasContado = $ventas->where('tipo', 'contado')->sum('total');
        $ventasCredito = $ventas->where('tipo', 'credito')->sum('total');
        
        return view('reportes.ventas', compact(
            'ventas',
            'totalVentas',
            'ventasContado',
            'ventasCredito'
        ));
    }

    public function compras(Request $request)
    {
        $query = Compra::with(['proveedor', 'items.materiaPrima']);
        
        if ($request->fecha_inicio) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        $compras = $query->orderBy('fecha', 'desc')->get();
        
        $totalCompras = $compras->sum('total');
        $comprasContado = $compras->where('tipo', 'contado')->sum('total');
        $comprasCredito = $compras->where('tipo', 'credito')->sum('total');
        
        return view('reportes.compras', compact(
            'compras',
            'totalCompras',
            'comprasContado',
            'comprasCredito'
        ));
    }

    public function flujoCaja(Request $request)
    {
        $query = CierreDiario::query();
        
        if ($request->fecha_inicio) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        $cierres = $query->orderBy('fecha', 'desc')->get();
        
        return view('reportes.flujo-caja', compact('cierres'));
    }
}
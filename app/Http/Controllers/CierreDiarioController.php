<?php

namespace App\Http\Controllers;

use App\Models\CierreDiario;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\MovimientoBancario;
use App\Models\PagoCliente;
use App\Models\PagoProveedor;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CierreDiarioController extends Controller
{
    public function index()
    {
        $query = CierreDiario::query();
        
        if (request('fecha')) {
            $query->whereDate('fecha', request('fecha'));
        }
        
        if (request('usuario')) {
            $query->where('usuario_id', request('usuario'));
        }
        
        $cierres = $query->orderBy('fecha', 'desc')->paginate(30);
        $usuarios = User::orderBy('name')->get();
        
        return view('cierre.index', compact('cierres', 'usuarios'));
    }

    public function create()
    {
        // Verificar si ya existe cierre para hoy
        if (CierreDiario::whereDate('fecha', Carbon::today())->exists()) {
            return redirect()->route('cierre.index')->with('error', 'Ya se realizó el cierre para hoy');
        }

        $ultimoCierre = CierreDiario::latest('fecha')->first();
        $saldoInicial = $ultimoCierre ? $ultimoCierre->saldo_final : 0;

        $hoy = Carbon::today();

        // Obtener ventas del día
        $ventasContado = Venta::whereDate('fecha', $hoy)
            ->where('tipo', 'contado')
            ->sum('total');

        $ventasCredito = Venta::whereDate('fecha', $hoy)
            ->where('tipo', 'credito')
            ->sum('total');

        // Obtener compras del día
        $comprasContado = Compra::whereDate('fecha', $hoy)
            ->where('tipo', 'contado')
            ->sum('total');

        $comprasCredito = Compra::whereDate('fecha', $hoy)
            ->where('tipo', 'credito')
            ->sum('total');

        // Obtener pagos recibidos de clientes (abonos a créditos)
        $pagosClientes = PagoCliente::whereDate('fecha_pago', $hoy)
            ->sum('monto');

        // Obtener pagos a proveedores (abonos a créditos)
        $pagosProveedores = PagoProveedor::whereDate('fecha_pago', $hoy)
            ->sum('monto');

        // Obtener movimientos bancarios
        $movimientos = MovimientoBancario::whereDate('fecha', $hoy)
            ->with('cuentaBancaria')
            ->get();

        $ingresosBancarios = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $egresosBancarios = $movimientos->where('tipo', 'egreso')->sum('monto');

        // Calcular saldo final teórico (sin gastos varios)
        $saldoFinal = $saldoInicial 
            + $ventasContado 
            + $pagosClientes 
            + $ingresosBancarios
            - $comprasContado 
            - $pagosProveedores 
            - $egresosBancarios;

        return view('cierre.create', compact(
            'saldoInicial',
            'ventasContado',
            'ventasCredito',
            'comprasContado',
            'comprasCredito',
            'pagosClientes',
            'pagosProveedores',
            'movimientos',
            'ingresosBancarios',
            'egresosBancarios',
            'saldoFinal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'saldo_inicial' => 'required|numeric',
            'saldo_final' => 'required|numeric',
            'ventas_contado' => 'required|numeric|min:0',
            'ventas_credito' => 'required|numeric|min:0',
            'compras_contado' => 'required|numeric|min:0',
            'compras_credito' => 'required|numeric|min:0',
            'pagos_clientes' => 'required|numeric|min:0',
            'pagos_proveedores' => 'required|numeric|min:0',
            'ingresos_bancarios' => 'required|numeric|min:0',
            'egresos_bancarios' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string'
        ]);

        // Verificar si ya existe cierre para hoy
        if (CierreDiario::whereDate('fecha', Carbon::today())->exists()) {
            return back()->with('error', 'Ya se realizó el cierre para hoy');
        }

        CierreDiario::create([
            'fecha' => Carbon::today(),
            'saldo_inicial' => $request->saldo_inicial,
            'ventas_contado' => $request->ventas_contado,
            'ventas_credito' => $request->ventas_credito,
            'compras_contado' => $request->compras_contado,
            'compras_credito' => $request->compras_credito,
            'pagos_clientes' => $request->pagos_clientes,
            'pagos_proveedores' => $request->pagos_proveedores,
            'ingresos_bancarios' => $request->ingresos_bancarios,
            'egresos_bancarios' => $request->egresos_bancarios,
            'saldo_final' => $request->saldo_final,
            'observaciones' => $request->observaciones,
            'usuario_id' => auth()->id()
        ]);

        return redirect()->route('cierre.index')->with('success', 'Cierre diario registrado correctamente');
    }

    public function show($id)
    {
        $cierre = CierreDiario::with('usuario')->findOrFail($id);
        
        // Obtener todas las transacciones del día
        $ventas = Venta::whereDate('fecha', $cierre->fecha)
            ->with(['cliente', 'items'])
            ->get();
            
        $compras = Compra::whereDate('fecha', $cierre->fecha)
            ->with(['proveedor', 'items'])
            ->get();
            
        $pagosClientes = PagoCliente::whereDate('fecha_pago', $cierre->fecha)
            ->with(['venta', 'cuentaBancaria'])
            ->get();
            
        $pagosProveedores = PagoProveedor::whereDate('fecha_pago', $cierre->fecha)
            ->with(['compra', 'cuentaBancaria'])
            ->get();
            
        $movimientosBancarios = MovimientoBancario::whereDate('fecha', $cierre->fecha)
            ->with(['cuentaBancaria'])
            ->get();
            
        return view('cierre.show', compact(
            'cierre',
            'ventas',
            'compras',
            'pagosClientes',
            'pagosProveedores',
            'movimientosBancarios'
        ));
    }

    public function reporteFlujoCaja(Request $request)
    {
        $query = CierreDiario::query();
        
        if ($request->fecha_inicio) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        $cierres = $query->orderBy('fecha', 'desc')->get();
        
        return view('cierre.reporte-flujo-caja', compact('cierres'));
    }
}
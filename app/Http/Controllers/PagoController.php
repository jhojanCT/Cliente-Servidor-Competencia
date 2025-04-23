<?php

namespace App\Http\Controllers;

use App\Models\PagoCliente;
use App\Models\PagoProveedor;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\CuentaBancaria;
use App\Models\MovimientoBancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    // Pagos a clientes (para ventas a crédito)
    public function indexClientes()
    {
        $pagos = PagoCliente::with(['venta', 'venta.cliente'])->get();
        return view('pagos.clientes.index', compact('pagos'));
    }

    public function createCliente(Venta $venta)
    {
        $cuentas = CuentaBancaria::all();
        return view('pagos.clientes.create', compact('venta', 'cuentas'));
    }

    public function storeCliente(Request $request, Venta $venta)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.01|max:' . $venta->saldoPendiente(),
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|string',
            'cuenta_bancaria_id' => 'nullable|exists:cuentas_bancarias,id',
            'comprobante' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $venta) {
            $pago = PagoCliente::create([
                'venta_id' => $venta->id,
                'monto' => $request->monto,
                'fecha_pago' => $request->fecha_pago,
                'metodo_pago' => $request->metodo_pago,
                'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                'comprobante' => $request->comprobante
            ]);

            // Crear movimiento bancario si se especificó una cuenta
            if ($request->cuenta_bancaria_id) {
                MovimientoBancario::create([
                    'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                    'tipo' => 'ingreso',
                    'monto' => $request->monto,
                    'fecha' => $request->fecha_pago,
                    'concepto' => 'Pago de venta a crédito #' . $venta->id,
                    'movimiento_type' => 'App\\Models\\PagoCliente',
                    'movimiento_id' => $pago->id
                ]);
            }

            if ($venta->saldoPendiente() <= 0) {
                $venta->update(['pagada' => true]);
            }
        });

        return redirect()->route('ventas.show', $venta)->with('success', 'Pago registrado correctamente');
    }

    // Pagos a proveedores (para compras a crédito)
    public function indexProveedores()
    {
        $pagos = PagoProveedor::with(['compra', 'compra.proveedor'])->get();
        return view('pagos.proveedores.index', compact('pagos'));
    }

    public function createProveedor(Compra $compra)
    {
        $cuentas = CuentaBancaria::all();
        return view('pagos.proveedores.create', compact('compra', 'cuentas'));
    }

    public function storeProveedor(Request $request, Compra $compra)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.01|max:' . $compra->saldoPendiente(),
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|string',
            'cuenta_bancaria_id' => 'nullable|exists:cuentas_bancarias,id',
            'comprobante' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $compra) {
            $pago = PagoProveedor::create([
                'compra_id' => $compra->id,
                'monto' => $request->monto,
                'fecha_pago' => $request->fecha_pago,
                'metodo_pago' => $request->metodo_pago,
                'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                'comprobante' => $request->comprobante
            ]);

            // Crear movimiento bancario si se especificó una cuenta
            if ($request->cuenta_bancaria_id) {
                MovimientoBancario::create([
                    'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                    'tipo' => 'egreso',
                    'monto' => $request->monto,
                    'fecha' => $request->fecha_pago,
                    'concepto' => 'Pago de compra a crédito #' . $compra->id,
                    'movimiento_type' => 'App\\Models\\PagoProveedor',
                    'movimiento_id' => $pago->id
                ]);
            }

            if ($compra->saldoPendiente() <= 0) {
                $compra->update(['pagada' => true]);
            }
        });

        return redirect()->route('compras.show', $compra)->with('success', 'Pago registrado correctamente');
    }
}
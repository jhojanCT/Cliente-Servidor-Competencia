<?php

namespace App\Http\Controllers;

use App\Models\MovimientoBancario;
use App\Models\CuentaBancaria;
use Illuminate\Http\Request;

class MovimientoBancarioController extends Controller
{
    public function create(CuentaBancaria $cuenta)
    {
        return view('movimientos-bancarios.create', compact('cuenta'));
    }

    public function store(Request $request, CuentaBancaria $cuenta)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date'
        ]);

        $movimiento = MovimientoBancario::create([
            'cuenta_bancaria_id' => $cuenta->id,
            'tipo' => $validated['tipo'],
            'concepto' => $validated['concepto'],
            'monto' => $validated['monto'],
            'fecha' => $validated['fecha']
        ]);

        return redirect()->route('cuentas-bancarias.show', $cuenta)->with('success', 'Movimiento registrado');
    }
}
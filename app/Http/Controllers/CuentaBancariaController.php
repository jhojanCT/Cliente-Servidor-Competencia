<?php

namespace App\Http\Controllers;

use App\Models\CuentaBancaria;
use Illuminate\Http\Request;

class CuentaBancariaController extends Controller
{
    public function index()
    {
        $cuentas = CuentaBancaria::all();
        return view('cuentas-bancarias.index', compact('cuentas'));
    }

    public function create()
    {
        return view('cuentas-bancarias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_banco' => 'required|string|max:255',
            'numero_cuenta' => 'required|string|max:50',
            'saldo' => 'required|numeric|min:0'
        ]);

        CuentaBancaria::create($validated);

        return redirect()->route('cuentas-bancarias.index')->with('success', 'Cuenta bancaria creada');
    }

    public function show(CuentaBancaria $cuenta)
    {
        $movimientos = $cuenta->movimientosBancarios()->orderBy('fecha', 'desc')->get();
        return view('cuentas-bancarias.show', compact('cuenta', 'movimientos'));
    }

    public function edit(CuentaBancaria $cuenta)
    {
        return view('cuentas-bancarias.edit', compact('cuenta'));
    }

    public function update(Request $request, CuentaBancaria $cuenta)
    {
        $validated = $request->validate([
            'nombre_banco' => 'required|string|max:255',
            'numero_cuenta' => 'required|string|max:50',
            'saldo' => 'required|numeric|min:0'
        ]);

        $cuenta->update($validated);

        return redirect()->route('cuentas-bancarias.index')->with('success', 'Cuenta bancaria actualizada');
    }

    public function destroy(CuentaBancaria $cuenta)
    {
        $cuenta->delete();
        return redirect()->route('cuentas-bancarias.index')->with('success', 'Cuenta bancaria eliminada');
    }
}
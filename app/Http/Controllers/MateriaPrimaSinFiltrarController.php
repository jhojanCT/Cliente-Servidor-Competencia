<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrimaSinFiltrar;
use Illuminate\Http\Request;

class MateriaPrimaSinFiltrarController extends Controller
{
    public function index()
    {
        $materiasPrimas = MateriaPrimaSinFiltrar::all();
        return view('materia-prima-sin-filtrar.index', compact('materiasPrimas'));
    }

    public function create()
    {
        return view('materia-prima-sin-filtrar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string'
        ]);

        MateriaPrimaSinFiltrar::create($validated);

        return redirect()->route('materia-prima-sin-filtrar.index')->with('success', 'Materia prima creada');
    }

    public function show(MateriaPrimaSinFiltrar $materiaPrima)
    {
        return view('materia-prima-sin-filtrar.show', compact('materiaPrima'));
    }

    public function edit(MateriaPrimaSinFiltrar $materia_prima_sin_filtrar)
    {
        return view('materia-prima-sin-filtrar.edit', [
            'materiaPrima' => $materia_prima_sin_filtrar
        ]);
    }
    
    

    public function update(Request $request, MateriaPrimaSinFiltrar $materia_prima_sin_filtrar)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string'
        ]);

        $materia_prima_sin_filtrar->update($validated);

        return redirect()->route('materia-prima-sin-filtrar.index')->with('success', 'Materia prima actualizada');
    }

    public function destroy(MateriaPrimaSinFiltrar $materiaPrima)
    {
        $materiaPrima->delete();
        return redirect()->route('materia-prima-sin-filtrar.index')->with('success', 'Materia prima eliminada');
    }
}
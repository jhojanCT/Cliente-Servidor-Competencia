<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrimaFiltrada;
use Illuminate\Http\Request;

class MateriaPrimaFiltradaController extends Controller
{
    public function index()
    {
        $materiasPrimas = MateriaPrimaFiltrada::all();
        return view('materia-prima-filtrada.index', compact('materiasPrimas'));
    }

    public function show(MateriaPrimaFiltrada $materiaPrima)
    {
        return view('materia-prima-filtrada.show', compact('materiaPrima'));
    }
}
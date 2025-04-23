@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Materias Primas Filtradas</h1>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Unidad de Medida</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiasPrimas as $materia)
            <tr>
                <td>{{ $materia->nombre }}</td>
                <td>{{ $materia->unidad_medida }}</td>
                <td>{{ $materia->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nuevo Movimiento Bancario</h1>
    <h4>Cuenta: {{ $cuenta->nombre_banco }} - {{ $cuenta->numero_cuenta }}</h4>
    <h5>Saldo Actual: {{ number_format($cuenta->saldo, 2) }}</h5>
    
    <form action="{{ route('movimientos-bancarios.store', $cuenta->id) }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="tipo">Tipo de Movimiento:</label>
            <select class="form-control" id="tipo" name="tipo" required>
                <option value="ingreso">Ingreso</option>
                <option value="egreso">Egreso</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="concepto">Concepto:</label>
            <input type="text" class="form-control" id="concepto" name="concepto" required>
        </div>
        
        <div class="form-group">
            <label for="monto">Monto:</label>
            <input type="number" step="0.01" min="0.01" class="form-control" id="monto" name="monto" required>
        </div>
        
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('cuentas-bancarias.show', $cuenta->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva Cuenta Bancaria</h1>
    
    <form action="{{ route('cuentas-bancarias.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="nombre_banco">Nombre del Banco:</label>
            <input type="text" class="form-control" id="nombre_banco" name="nombre_banco" required>
        </div>
        
        <div class="form-group">
            <label for="numero_cuenta">Número de Cuenta:</label>
            <input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" required>
        </div>
        
        <div class="form-group">
            <label for="saldo">Saldo Inicial:</label>
            <input type="number" step="0.01" min="0" class="form-control" id="saldo" name="saldo" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('cuentas-bancarias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
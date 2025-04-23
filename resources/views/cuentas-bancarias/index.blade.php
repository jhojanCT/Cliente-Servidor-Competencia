@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0"><i class="fas fa-university"></i> Cuentas Bancarias</h1>
            <a href="{{ route('cuentas-bancarias.create') }}" class="btn btn-light">
                <i class="fas fa-plus"></i> Nueva Cuenta
            </a>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Banco</th>
                            <th>Número de Cuenta</th>
                            <th class="text-end">Saldo</th>
                            <th style="width: 200px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta->nombre_banco }}</td>
                            <td>{{ $cuenta->numero_cuenta }}</td>
                            <td class="text-end">Bs {{ number_format($cuenta->saldo, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('cuentas-bancarias.show', $cuenta->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cuentas-bancarias.edit', $cuenta->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cuentas-bancarias.destroy', $cuenta->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta bancaria?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
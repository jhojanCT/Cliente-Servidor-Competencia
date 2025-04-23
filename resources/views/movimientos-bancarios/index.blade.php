@extends('layouts.app')

@section('title', 'Movimientos Bancarios')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Historial de Movimientos</h5>
        <a href="{{ route('movimientos-bancarios.create') }}" class="btn btn-primary">Nuevo Movimiento</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cuenta</th>
                        <th>Concepto</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $movimiento)
                        <tr>
                            <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
                            <td>{{ $movimiento->cuentaBancaria->nombre_banco }}</td>
                            <td>{{ $movimiento->concepto }}</td>
                            <td>
                                <span class="badge bg-{{ $movimiento->tipo == 'ingreso' ? 'success' : 'danger' }}">
                                    {{ ucfirst($movimiento->tipo) }}
                                </span>
                            </td>
                            <td class="{{ $movimiento->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($movimiento->monto, 2) }}
                            </td>
                            <td>${{ number_format($movimiento->saldo_actual, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
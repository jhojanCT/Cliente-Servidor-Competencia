@extends('layouts.app')

@section('title', 'Detalle de Movimiento Bancario')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Movimiento #{{ $movimiento->id }}</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Cuenta Bancaria:</strong> {{ $movimiento->cuentaBancaria->nombre_banco }} - {{ $movimiento->cuentaBancaria->numero_cuenta }}</p>
                <p><strong>Fecha:</strong> {{ $movimiento->fecha->format('d/m/Y') }}</p>
                <p><strong>Tipo:</strong> 
                    <span class="badge bg-{{ $movimiento->tipo == 'ingreso' ? 'success' : 'danger' }}">
                        {{ ucfirst($movimiento->tipo) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Monto:</strong> 
                    <span class="{{ $movimiento->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($movimiento->monto, 2) }}
                    </span>
                </p>
                <p><strong>Saldo después del movimiento:</strong> ${{ number_format($movimiento->saldo_actual, 2) }}</p>
                <p><strong>Concepto:</strong> {{ $movimiento->concepto }}</p>
            </div>
        </div>

        @if($movimiento->movimiento_type)
            <div class="alert alert-info">
                <strong>Relacionado con:</strong> 
                @if($movimiento->movimiento_type == 'App\Models\Venta')
                    Venta #{{ $movimiento->movimiento->id }} ({{ $movimiento->movimiento->tipo }})
                @elseif($movimiento->movimiento_type == 'App\Models\Compra')
                    Compra #{{ $movimiento->movimiento->id }} ({{ $movimiento->movimiento->tipo }})
                @elseif($movimiento->movimiento_type == 'App\Models\PagoCliente')
                    Pago de cliente #{{ $movimiento->movimiento->id }}
                @elseif($movimiento->movimiento_type == 'App\Models\PagoProveedor')
                    Pago a proveedor #{{ $movimiento->movimiento->id }}
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
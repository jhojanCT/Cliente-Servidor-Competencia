@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-university me-2"></i>Detalle de Cuenta Bancaria
                    </h5>
                    <a href="{{ route('cuentas-bancarias.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-building me-2"></i>Banco:</strong> {{ $cuenta->nombre_banco }}</p>
                            <p><strong><i class="fas fa-hashtag me-2"></i>Número de Cuenta:</strong> {{ $cuenta->numero_cuenta }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-wallet me-2"></i>Saldo Actual:</strong> 
                                <span class="badge bg-{{ $cuenta->saldo >= 0 ? 'success' : 'danger' }} fs-6">
                                    Bs. {{ number_format($cuenta->saldo, 2, ',', '.') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <a href="{{ route('movimientos-bancarios.create', ['cuenta' => $cuenta->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Crear Movimiento
                </a>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Historial de Movimientos
                    </h5>
                    <span class="badge bg-light text-secondary">Total: {{ $movimientos->count() }}</span>
                </div>
                <div class="card-body">
                    @if($movimientos->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay movimientos registrados para esta cuenta.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Concepto</th>
                                        <th>Detalles</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-end">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movimientos as $movimiento)
                                    <tr>
                                        <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $movimiento->tipo == 'ingreso' ? 'success' : 'danger' }}">
                                                <i class="fas fa-{{ $movimiento->tipo == 'ingreso' ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                                {{ ucfirst($movimiento->tipo) }}
                                            </span>
                                        </td>
                                        <td>{{ $movimiento->concepto }}</td>
                                        <td>
                                            @if($movimiento->movimiento_type)
                                                @if($movimiento->movimiento_type == 'App\\Models\\Venta' && $movimiento->movimiento)
                                                    <div>
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-shopping-cart me-1"></i>Venta #{{ $movimiento->movimiento_id }}
                                                        </span>
                                                        @if($movimiento->movimiento->cliente)
                                                            <div class="mt-1 small">
                                                                <strong>Cliente:</strong> {{ $movimiento->movimiento->cliente->nombre }}<br>
                                                                @if($movimiento->movimiento->items && $movimiento->movimiento->items->count() > 0)
                                                                    <strong>Productos:</strong>
                                                                    <ul class="mb-0 ps-3">
                                                                        @foreach($movimiento->movimiento->items as $item)
                                                                            @if($item->producto)
                                                                                <li>{{ $item->cantidad }} {{ $item->unidad_medida }} de {{ $item->producto->nombre }}</li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($movimiento->movimiento_type == 'App\\Models\\Compra' && $movimiento->movimiento)
                                                    <div>
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-truck me-1"></i>Compra #{{ $movimiento->movimiento_id }}
                                                        </span>
                                                        @if($movimiento->movimiento->proveedor)
                                                            <div class="mt-1 small">
                                                                <strong>Proveedor:</strong> {{ $movimiento->movimiento->proveedor->nombre }}<br>
                                                                @if($movimiento->movimiento->items && $movimiento->movimiento->items->count() > 0)
                                                                    <strong>Productos:</strong>
                                                                    <ul class="mb-0 ps-3">
                                                                        @foreach($movimiento->movimiento->items as $item)
                                                                            @if($item->producto)
                                                                                <li>{{ $item->cantidad }} {{ $item->unidad_medida }} de {{ $item->producto->nombre }}</li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($movimiento->movimiento_type == 'App\\Models\\PagoCliente' && $movimiento->movimiento)
                                                    <div>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-user-check me-1"></i>Pago de Cliente
                                                        </span>
                                                        @if($movimiento->movimiento->venta && $movimiento->movimiento->venta->cliente)
                                                            <div class="mt-1 small">
                                                                <strong>Cliente:</strong> {{ $movimiento->movimiento->venta->cliente->nombre }}<br>
                                                                <strong>Venta:</strong> #{{ $movimiento->movimiento->venta->id }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($movimiento->movimiento_type == 'App\\Models\\PagoProveedor' && $movimiento->movimiento)
                                                    <div>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-user-tie me-1"></i>Pago a Proveedor
                                                        </span>
                                                        @if($movimiento->movimiento->compra && $movimiento->movimiento->compra->proveedor)
                                                            <div class="mt-1 small">
                                                                <strong>Proveedor:</strong> {{ $movimiento->movimiento->compra->proveedor->nombre }}<br>
                                                                <strong>Compra:</strong> #{{ $movimiento->movimiento->compra->id }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-exchange-alt me-1"></i>Movimiento Manual
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-{{ $movimiento->tipo == 'ingreso' ? 'success' : 'danger' }}">
                                                Bs. {{ number_format($movimiento->monto, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ $movimiento->saldo_actual >= 0 ? 'success' : 'danger' }}">
                                                Bs. {{ number_format($movimiento->saldo_actual, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
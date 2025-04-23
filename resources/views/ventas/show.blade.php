@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Venta</h1>
    
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Información General</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Cliente:</strong> {{ $venta->cliente ? $venta->cliente->nombre : 'Consumidor Final' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Fecha:</strong> {{ $venta->fecha->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Tipo:</strong> {{ ucfirst($venta->tipo) }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Total:</strong> Bs. {{ number_format($venta->total, 2) }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Estado:</strong> 
                        @if($venta->tipo == 'credito')
                            @if($venta->pagada)
                                <span class="badge badge-success">Pagada</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        @else
                            <span class="badge badge-success">Pagada</span>
                        @endif
                    </p>
                </div>
            </div>
            
            @if($venta->tipo == 'credito' && !$venta->pagada)
            <div class="mt-3">
                <a href="{{ route('pagos.clientes.create', $venta->id) }}" class="btn btn-primary">Registrar Pago</a>
            </div>
            @endif
        </div>
    </div>
    
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Ítems de Venta</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->items as $item)
                        <tr>
                            <td>{{ ucfirst($item->tipo_item) }}</td>
                            <td>
                                @if($item->tipo_item == 'materia_prima_filtrada')
                                    {{ $item->materiaPrimaFiltrada->nombre }}
                                @else
                                    {{ $item->producto->nombre }}
                                @endif
                            </td>
                            <td>
                                {{ $item->cantidad }} 
                                @if($item->tipo_item == 'materia_prima_filtrada')
                                    {{ $item->materiaPrimaFiltrada->unidad_medida }}
                                @else
                                    unidades
                                @endif
                            </td>
                            <td>Bs. {{ number_format($item->precio_unitario, 2) }}</td>
                            <td>Bs. {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($venta->tipo == 'credito')
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Pagos Registrados</div>
        <div class="card-body">
            @if($venta->pagos->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Cuenta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->pagos as $pago)
                        <tr>
                            <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                            <td>Bs. {{ number_format($pago->monto, 2) }}</td>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                            <td>{{ $pago->cuentaBancaria->nombre }} - {{ $pago->cuentaBancaria->numero_cuenta }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total Pagado:</th>
                            <th>Bs. {{ number_format($venta->pagos->sum('monto'), 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-right">Saldo Pendiente:</th>
                            <th>Bs. {{ number_format($venta->total - $venta->pagos->sum('monto'), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <p class="text-center">No hay pagos registrados para esta venta.</p>
            @endif
        </div>
    </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
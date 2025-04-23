@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Reporte de Flujo de Caja</h1>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <a href="{{ route('cierre.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filtros de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reporte.flujo-caja') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search"></i> Generar Reporte
                    </button>
                    <a href="{{ route('reporte.flujo-caja') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    @if(request()->has('fecha_inicio'))
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Resumen del Período: {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d/m/Y') }} al {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d/m/Y') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Total Ingresos</h5>
                            <p class="h4 text-success">
                                Bs {{ number_format($cierres->sum(function($cierre) {
                                    return $cierre->ventas_contado + $cierre->pagos_clientes + $cierre->ingresos_bancarios;
                                }), 2, ',', '.') }}
                            </p>
                            <small class="text-muted">
                                Ventas: Bs {{ number_format($cierres->sum('ventas_contado'), 2, ',', '.') }}<br>
                                Pagos: Bs {{ number_format($cierres->sum('pagos_clientes'), 2, ',', '.') }}<br>
                                Bancos: Bs {{ number_format($cierres->sum('ingresos_bancarios'), 2, ',', '.') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Total Egresos</h5>
                            <p class="h4 text-danger">
                                Bs {{ number_format($cierres->sum(function($cierre) {
                                    return $cierre->compras_contado + $cierre->pagos_proveedores + $cierre->egresos_bancarios;
                                }), 2, ',', '.') }}
                            </p>
                            <small class="text-muted">
                                Compras: Bs {{ number_format($cierres->sum('compras_contado'), 2, ',', '.') }}<br>
                                Pagos: Bs {{ number_format($cierres->sum('pagos_proveedores'), 2, ',', '.') }}<br>
                                Bancos: Bs {{ number_format($cierres->sum('egresos_bancarios'), 2, ',', '.') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Saldo Inicial</h5>
                            <p class="h4">
                                Bs {{ number_format($cierres->first()->saldo_inicial ?? 0, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Saldo Final</h5>
                            <p class="h4">
                                Bs {{ number_format($cierres->last()->saldo_final ?? 0, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detalle de Cierres</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Saldo Inicial</th>
                            <th>Ventas</th>
                            <th>Pagos Clientes</th>
                            <th>Ingresos Bancarios</th>
                            <th>Compras</th>
                            <th>Pagos Proveedores</th>
                            <th>Egresos Bancarios</th>
                            <th>Saldo Final</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cierres as $cierre)
                        <tr>
                            <td>{{ $cierre->fecha->format('d/m/Y') }}</td>
                            <td class="text-right">Bs {{ number_format($cierre->saldo_inicial, 2, ',', '.') }}</td>
                            <td class="text-right text-success">Bs {{ number_format($cierre->ventas_contado, 2, ',', '.') }}</td>
                            <td class="text-right text-success">Bs {{ number_format($cierre->pagos_clientes, 2, ',', '.') }}</td>
                            <td class="text-right text-success">Bs {{ number_format($cierre->ingresos_bancarios, 2, ',', '.') }}</td>
                            <td class="text-right text-danger">Bs {{ number_format($cierre->compras_contado, 2, ',', '.') }}</td>
                            <td class="text-right text-danger">Bs {{ number_format($cierre->pagos_proveedores, 2, ',', '.') }}</td>
                            <td class="text-right text-danger">Bs {{ number_format($cierre->egresos_bancarios, 2, ',', '.') }}</td>
                            <td class="text-right font-weight-bold">Bs {{ number_format($cierre->saldo_final, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('cierre.show', $cierre->id) }}" class="btn btn-sm btn-info" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay datos para el período seleccionado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header {
        display: none !important;
    }
    .card {
        break-inside: avoid;
    }
    .container {
        width: 100%;
        max-width: none;
    }
    .table {
        font-size: 12px;
    }
    .card-body {
        padding: 0;
    }
}
</style>
@endsection
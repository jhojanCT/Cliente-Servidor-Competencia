@extends('layouts.app')

@section('content')
<!-- Versión normal para pantalla -->
<div class="container d-print-none">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Cierre Diario</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-alt me-2"></i>{{ $cierre->fecha->format('d/m/Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cierre.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>Imprimir
        </button>
        </div>
    </div>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user fa-2x text-primary me-3"></i>
                        <div>
                            <small class="text-muted d-block">Usuario</small>
                            <strong>{{ $cierre->usuario->name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-2x text-success me-3"></i>
                        <div>
                            <small class="text-muted d-block">Fecha</small>
                            <strong>{{ $cierre->fecha->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-2x text-info me-3"></i>
                        <div>
                            <small class="text-muted d-block">Hora de Cierre</small>
                            <strong>{{ $cierre->created_at->format('H:i:s') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-arrow-circle-up me-2"></i>Ingresos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-wallet text-success me-2"></i>
                                Saldo Inicial
                            </div>
                            <strong>Bs. {{ number_format($cierre->saldo_inicial, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shopping-cart text-success me-2"></i>
                                Ventas al Contado
                            </div>
                            <strong>Bs. {{ number_format($cierre->ventas_contado, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-hand-holding-usd text-success me-2"></i>
                                Pagos de Clientes
                            </div>
                            <strong>Bs. {{ number_format($cierre->pagos_clientes, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-university text-success me-2"></i>
                                Ingresos Bancarios
                            </div>
                            <strong>Bs. {{ number_format($cierre->ingresos_bancarios, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <strong><i class="fas fa-calculator text-success me-2"></i>Total Ingresos</strong>
                            </div>
                            <strong class="text-success">
                                Bs. {{ number_format($cierre->ventas_contado + $cierre->pagos_clientes + $cierre->ingresos_bancarios, 2, ',', '.') }}
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-arrow-circle-down me-2"></i>Egresos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shopping-basket text-danger me-2"></i>
                                Compras al Contado
                            </div>
                            <strong>Bs. {{ number_format($cierre->compras_contado, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                Pagos a Proveedores
                            </div>
                            <strong>Bs. {{ number_format($cierre->pagos_proveedores, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-university text-danger me-2"></i>
                                Egresos Bancarios
                            </div>
                            <strong>Bs. {{ number_format($cierre->egresos_bancarios, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <strong><i class="fas fa-calculator text-danger me-2"></i>Total Egresos</strong>
                            </div>
                            <strong class="text-danger">
                                Bs. {{ number_format($cierre->compras_contado + $cierre->pagos_proveedores + $cierre->egresos_bancarios, 2, ',', '.') }}
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Créditos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-invoice-dollar text-info me-2"></i>
                                Ventas a Crédito
                            </div>
                            <strong>Bs. {{ number_format($cierre->ventas_credito, 2, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-invoice text-info me-2"></i>
                                Compras a Crédito
                            </div>
                            <strong>Bs. {{ number_format($cierre->compras_credito, 2, ',', '.') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Resumen Final</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-plus-circle text-success me-2"></i>
                                Total Ingresos
                            </div>
                            <strong class="text-success">
                                Bs. {{ number_format($cierre->ventas_contado + $cierre->pagos_clientes + $cierre->ingresos_bancarios, 2, ',', '.') }}
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-minus-circle text-danger me-2"></i>
                                Total Egresos
                            </div>
                            <strong class="text-danger">
                                Bs. {{ number_format($cierre->compras_contado + $cierre->pagos_proveedores + $cierre->egresos_bancarios, 2, ',', '.') }}
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <strong><i class="fas fa-balance-scale text-primary me-2"></i>Saldo Final</strong>
                            </div>
                            <strong class="text-primary">
                                Bs. {{ number_format($cierre->saldo_final, 2, ',', '.') }}
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle de Ventas -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Detalle de Ventas</h5>
            <span class="badge bg-light text-success">Total: {{ $ventas->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>N°</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th class="text-end">Total</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td><strong>#{{ $venta->id }}</strong></td>
                                <td>
                                    <i class="fas fa-user text-success me-2"></i>
                                    {{ $venta->cliente->nombre ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $venta->tipo == 'contado' ? 'success' : 'info' }}">
                                        {{ ucfirst($venta->tipo) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong>Bs. {{ number_format($venta->total, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <div class="small">
                                        @foreach($venta->items as $item)
                                            <div class="mb-1">
                                                <span class="text-muted">{{ $item->cantidad }} {{ $item->producto?->unidad_medida ?? 'unidades' }}</span>
                                                {{ $item->producto?->nombre ?? 'Producto eliminado' }}
                                                <span class="text-success">(Bs {{ number_format($item->subtotal, 2, ',', '.') }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <p class="mb-0">No hay ventas registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detalle de Compras -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-shopping-basket me-2"></i>Detalle de Compras</h5>
            <span class="badge bg-light text-danger">Total: {{ $compras->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th>N°</th>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th class="text-end">Total</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td><strong>#{{ $compra->id }}</strong></td>
                                <td>
                                    <i class="fas fa-building text-danger me-2"></i>
                                    {{ $compra->proveedor->nombre ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $compra->tipo == 'contado' ? 'danger' : 'info' }}">
                                        {{ ucfirst($compra->tipo) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong>Bs {{ number_format($compra->total, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <div class="small">
                                        @foreach($compra->items as $item)
                                            <div class="mb-1">
                                                @if($item->tipo_item == 'materia_prima')
                                                    <span class="text-muted">{{ $item->cantidad }} {{ $item->materiaPrima->unidad_medida ?? 'unidades' }}</span>
                                                    {{ $item->materiaPrima->nombre }}
                                                @else
                                                    <span class="text-muted">{{ $item->cantidad }} {{ $item->producto->unidad_medida ?? 'unidades' }}</span>
                                                    {{ $item->producto->nombre }}
                                                @endif
                                                <span class="text-danger">(Bs {{ number_format($item->subtotal, 2, ',', '.') }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-shopping-basket fa-2x mb-2"></i>
                                    <p class="mb-0">No hay compras registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detalle de Pagos de Clientes -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-hand-holding-usd me-2"></i>Detalle de Pagos de Clientes</h5>
            <span class="badge bg-light text-info">Total: {{ $pagosClientes->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>N° Venta</th>
                            <th>Cliente</th>
                            <th>Cuenta</th>
                            <th class="text-end">Monto</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagosClientes as $pago)
                            <tr>
                                <td><strong>#{{ $pago->venta->id }}</strong></td>
                                <td>
                                    <i class="fas fa-user text-info me-2"></i>
                                    {{ $pago->venta->cliente->nombre }}
                                </td>
                                <td>
                                    <i class="fas fa-university text-secondary me-2"></i>
                                    {{ $pago->cuentaBancaria->banco }} - {{ $pago->cuentaBancaria->numero_cuenta }}
                                </td>
                                <td class="text-end">
                                    <strong>Bs. {{ number_format($pago->monto, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        {{ ucfirst($pago->metodo_pago) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-hand-holding-usd fa-2x mb-3 d-block"></i>
                                    No hay pagos de clientes registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detalle de Pagos a Proveedores -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Detalle de Pagos a Proveedores</h5>
            <span class="badge bg-light text-warning">Total: {{ $pagosProveedores->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-warning">
                        <tr>
                            <th>N° Compra</th>
                            <th>Proveedor</th>
                            <th>Cuenta</th>
                            <th class="text-end">Monto</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagosProveedores as $pago)
                            <tr>
                                <td><strong>#{{ $pago->compra->id }}</strong></td>
                                <td>
                                    <i class="fas fa-building text-warning me-2"></i>
                                    {{ $pago->compra->proveedor->nombre }}
                                </td>
                                <td>
                                    <i class="fas fa-university text-secondary me-2"></i>
                                    {{ $pago->cuentaBancaria->banco }} - {{ $pago->cuentaBancaria->numero_cuenta }}
                                </td>
                                <td class="text-end">
                                    <strong>Bs. {{ number_format($pago->monto, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        {{ ucfirst($pago->metodo_pago) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-money-bill-wave fa-2x mb-3 d-block"></i>
                                    No hay pagos a proveedores registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detalle de Movimientos Bancarios -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-university me-2"></i>Detalle de Movimientos Bancarios</h5>
            <span class="badge bg-light text-secondary">Total: {{ $movimientosBancarios->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>Cuenta</th>
                            <th>Tipo</th>
                            <th>Concepto</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientosBancarios as $movimiento)
                            <tr>
                                <td>
                                    <i class="fas fa-university text-secondary me-2"></i>
                                    {{ $movimiento->cuentaBancaria->banco }} - {{ $movimiento->cuentaBancaria->numero_cuenta }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $movimiento->tipo == 'ingreso' ? 'success' : 'danger' }}">
                                        <i class="fas fa-{{ $movimiento->tipo == 'ingreso' ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ ucfirst($movimiento->tipo) }}
                                    </span>
                                </td>
                                <td>{{ $movimiento->concepto }}</td>
                                <td class="text-end">
                                    <strong class="text-{{ $movimiento->tipo == 'ingreso' ? 'success' : 'danger' }}">
                                        Bs. {{ number_format($movimiento->monto, 2, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-university fa-2x mb-3 d-block"></i>
                                    No hay movimientos bancarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Versión simplificada para impresión -->
<div class="container-fluid d-none d-print-block">
    <div class="text-center mb-4">
        <h2 class="mb-1">Reporte de Cierre Diario</h2>
        <p class="mb-0">Fecha: {{ $cierre->fecha->format('d/m/Y') }}</p>
        <p class="mb-0">Usuario: {{ $cierre->usuario->name ?? 'N/A' }}</p>
        <p>Hora: {{ $cierre->created_at->format('H:i:s') }}</p>
    </div>

    <!-- Resumen General -->
    <div class="mb-4">
        <h4 class="border-bottom pb-2">1. Resumen General</h4>
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td width="50%"><strong>Saldo Inicial:</strong></td>
                    <td class="text-end">Bs. {{ number_format($cierre->saldo_inicial, 2, ',', '.') }}</td>
                </tr>
                <tr class="table-success">
                    <td><strong>Total Ingresos:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($cierre->ventas_contado + $cierre->pagos_clientes + $cierre->ingresos_bancarios, 2, ',', '.') }}</strong></td>
                </tr>
                <tr class="table-danger">
                    <td><strong>Total Egresos:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($cierre->compras_contado + $cierre->pagos_proveedores + $cierre->egresos_bancarios, 2, ',', '.') }}</strong></td>
                </tr>
                <tr class="table-primary">
                    <td><strong>Saldo Final:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($cierre->saldo_final, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detalle de Ingresos -->
    <div class="mb-4">
        <h4 class="border-bottom pb-2">2. Detalle de Ingresos</h4>
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td width="50%">Ventas al Contado:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->ventas_contado, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ventas a Crédito:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->ventas_credito, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pagos de Clientes:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->pagos_clientes, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ingresos Bancarios:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->ingresos_bancarios, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detalle de Egresos -->
    <div class="mb-4">
        <h4 class="border-bottom pb-2">3. Detalle de Egresos</h4>
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td width="50%">Compras al Contado:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->compras_contado, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Compras a Crédito:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->compras_credito, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pagos a Proveedores:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->pagos_proveedores, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Egresos Bancarios:</td>
                    <td class="text-end">Bs. {{ number_format($cierre->egresos_bancarios, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detalle de Ventas -->
    @if($ventas->count() > 0)
    <div class="mb-4">
        <h4 class="border-bottom pb-2">4. Detalle de Ventas</h4>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Items</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td>#{{ $venta->id }}</td>
                    <td>{{ $venta->cliente->nombre ?? 'N/A' }}</td>
                    <td>{{ ucfirst($venta->tipo) }}</td>
                    <td>
                        <div class="small">
                            @foreach($venta->items as $item)
                                <div class="mb-1">
                                    <span class="text-muted">{{ $item->cantidad }} {{ $item->producto?->unidad_medida ?? 'unidades' }}</span>
                                    {{ $item->producto?->nombre ?? 'Producto eliminado' }}
                                    <span class="text-success">(Bs {{ number_format($item->subtotal, 2, ',', '.') }})</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-end">Bs. {{ number_format($venta->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-success">
                    <td colspan="4"><strong>Total Ventas:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($ventas->sum('total'), 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- Detalle de Compras -->
    @if($compras->count() > 0)
    <div class="mb-4">
        <h4 class="border-bottom pb-2">5. Detalle de Compras</h4>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Proveedor</th>
                    <th>Tipo</th>
                    <th>Items</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compras as $compra)
                <tr>
                    <td>#{{ $compra->id }}</td>
                    <td>{{ $compra->proveedor->nombre }}</td>
                    <td>{{ ucfirst($compra->tipo) }}</td>
                    <td>
                        <div class="small">
                            @foreach($compra->items as $item)
                                <div class="mb-1">
                                    @if($item->tipo_item == 'materia_prima')
                                        <span class="text-muted">{{ $item->cantidad }} {{ $item->materiaPrima->unidad_medida ?? 'unidades' }}</span>
                                        {{ $item->materiaPrima->nombre }}
                                    @else
                                        <span class="text-muted">{{ $item->cantidad }} {{ $item->producto->unidad_medida ?? 'unidades' }}</span>
                                        {{ $item->producto->nombre }}
                                    @endif
                                    <span class="text-danger">(Bs {{ number_format($item->subtotal, 2, ',', '.') }})</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-end">Bs. {{ number_format($compra->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-danger">
                    <td colspan="4"><strong>Total Compras:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($compras->sum('total'), 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- Detalle de Pagos de Clientes -->
    @if($pagosClientes->count() > 0)
    <div class="mb-4">
        <h4 class="border-bottom pb-2">6. Detalle de Pagos de Clientes</h4>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>N° Venta</th>
                    <th>Cliente</th>
                    <th>Método</th>
                    <th>Cuenta</th>
                    <th class="text-end">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagosClientes as $pago)
                <tr>
                    <td>#{{ $pago->venta->id }}</td>
                    <td>{{ $pago->venta->cliente->nombre }}</td>
                    <td>{{ ucfirst($pago->metodo_pago) }}</td>
                    <td>{{ $pago->cuentaBancaria->banco }} - {{ $pago->cuentaBancaria->numero_cuenta }}</td>
                    <td class="text-end">Bs. {{ number_format($pago->monto, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-info">
                    <td colspan="4"><strong>Total Pagos de Clientes:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($pagosClientes->sum('monto'), 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- Detalle de Pagos a Proveedores -->
    @if($pagosProveedores->count() > 0)
    <div class="mb-4">
        <h4 class="border-bottom pb-2">7. Detalle de Pagos a Proveedores</h4>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>N° Compra</th>
                    <th>Proveedor</th>
                    <th>Método</th>
                    <th>Cuenta</th>
                    <th class="text-end">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagosProveedores as $pago)
                <tr>
                    <td>#{{ $pago->compra->id }}</td>
                    <td>{{ $pago->compra->proveedor->nombre }}</td>
                    <td>{{ ucfirst($pago->metodo_pago) }}</td>
                    <td>{{ $pago->cuentaBancaria->banco }} - {{ $pago->cuentaBancaria->numero_cuenta }}</td>
                    <td class="text-end">Bs. {{ number_format($pago->monto, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-warning">
                    <td colspan="4"><strong>Total Pagos a Proveedores:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($pagosProveedores->sum('monto'), 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
        </div>
    @endif

    <!-- Detalle de Movimientos Bancarios -->
    @if($movimientosBancarios->count() > 0)
    <div class="mb-4">
        <h4 class="border-bottom pb-2">8. Detalle de Movimientos Bancarios</h4>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th class="text-end">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientosBancarios as $movimiento)
                <tr>
                    <td>{{ $movimiento->cuentaBancaria->banco }} - {{ $movimiento->cuentaBancaria->numero_cuenta }}</td>
                    <td>{{ ucfirst($movimiento->tipo) }}</td>
                    <td>{{ $movimiento->concepto }}</td>
                    <td class="text-end">Bs. {{ number_format($movimiento->monto, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <td colspan="3"><strong>Total Movimientos:</strong></td>
                    <td class="text-end"><strong>Bs. {{ number_format($movimientosBancarios->sum('monto'), 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
    
    <div class="mt-4 text-center">
        <small>Impreso el {{ now()->format('d/m/Y H:i:s') }}</small>
    </div>
</div>

<style>
@media print {
    body {
        font-size: 11px;
    }
    .table {
        font-size: 11px;
    }
    .table td, .table th {
        padding: 0.2rem;
    }
    h2 {
        font-size: 16px;
    }
    h4 {
        font-size: 13px;
        margin-top: 1rem;
    }
    .mb-4 {
        margin-bottom: 0.5rem !important;
    }
    .table-sm td, .table-sm th {
        padding: 0.1rem;
    }
    .border-bottom {
        border-bottom: 1px solid #000 !important;
    }
}
</style>
@endsection
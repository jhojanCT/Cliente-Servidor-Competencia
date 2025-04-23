@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">
                <i class="fas fa-cash-register me-2"></i>Realizar Cierre Diario
                <small class="d-block h6 mb-0 mt-1">{{ now()->format('d/m/Y') }}</small>
            </h1>
            <a href="{{ route('cierre.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
        
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form action="{{ route('cierre.store') }}" method="POST">
                @csrf
                
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <div class="card h-100 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Resumen de Ventas</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Saldo Inicial:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="saldo_inicial" value="{{ number_format($saldoInicial, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ventas al Contado:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="ventas_contado" value="{{ number_format($ventasContado, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ventas a Crédito:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="ventas_credito" value="{{ number_format($ventasCredito, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Ventas:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end fw-bold text-success" value="{{ number_format($ventasContado + $ventasCredito, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Resumen de Compras</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Compras al Contado:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="compras_contado" value="{{ number_format($comprasContado, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Compras a Crédito:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="compras_credito" value="{{ number_format($comprasCredito, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Compras:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end fw-bold text-danger" value="{{ number_format($comprasContado + $comprasCredito, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <div class="card h-100 border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Pagos y Movimientos</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pagos de Clientes:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="pagos_clientes" value="{{ number_format($pagosClientes, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pagos a Proveedores:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="pagos_proveedores" value="{{ number_format($pagosProveedores, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-university me-2"></i>Movimientos Bancarios</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ingresos Bancarios:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="ingresos_bancarios" value="{{ number_format($ingresosBancarios, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Egresos Bancarios:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="egresos_bancarios" value="{{ number_format($egresosBancarios, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen Final</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Saldo Final Calculado:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end fw-bold" name="saldo_final" value="{{ number_format($saldoFinal, 2, '.', '') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Saldo en Efectivo:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs</span>
                                        <input type="number" step="0.01" class="form-control text-end" name="saldo_efectivo" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones:</label>
                            <textarea name="observaciones" class="form-control" rows="3" placeholder="Ingrese cualquier observación relevante sobre el cierre"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Confirmar Cierre Diario
                    </button>
                    <a href="{{ route('cierre.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Cierres Diarios</h1>
        <div>
            <a href="{{ route('cierre.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Realizar Cierre Diario
            </a>
            <a href="{{ route('reporte.flujo-caja') }}" class="btn btn-info ml-2">
                <i class="fas fa-file-alt"></i> Reporte Flujo de Caja
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('cierre.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha" class="form-label">
                        <i class="fas fa-calendar me-1"></i>Fecha:
                    </label>
                    <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-4">
                    <label for="usuario" class="form-label">
                        <i class="fas fa-user me-1"></i>Usuario:
                    </label>
                    <select name="usuario" class="form-select">
                        <option value="">Todos los usuarios</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ request('usuario') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Cierres</h5>
            <span class="badge bg-light text-primary">Total: {{ $cierres->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Fecha</th>
                            <th class="text-end">Saldo Inicial</th>
                            <th class="text-end">Ventas Totales</th>
                            <th class="text-end">Compras Totales</th>
                            <th class="text-end">Saldo Final</th>
                            <th>Usuario</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cierres as $cierre)
                        <tr>
                            <td class="text-center">
                                <i class="fas fa-calendar-day text-primary me-1"></i>
                                {{ $cierre->fecha->format('d/m/Y') }}
                            </td>
                            <td class="text-end">
                                <span class="text-muted">Bs.</span> 
                                {{ number_format($cierre->saldo_inicial, 2, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <span class="text-success">
                                    <span class="text-muted">Bs.</span> 
                                    {{ number_format($cierre->ventas_contado + $cierre->ventas_credito, 2, ',', '.') }}
                                </span>
                                <small class="d-block text-muted">
                                    Contado: Bs. {{ number_format($cierre->ventas_contado, 2, ',', '.') }}
                                </small>
                                <small class="d-block text-muted">
                                    Crédito: Bs. {{ number_format($cierre->ventas_credito, 2, ',', '.') }}
                                </small>
                            </td>
                            <td class="text-end">
                                <span class="text-danger">
                                    <span class="text-muted">Bs.</span> 
                                    {{ number_format($cierre->compras_contado + $cierre->compras_credito, 2, ',', '.') }}
                                </span>
                                <small class="d-block text-muted">
                                    Contado: Bs. {{ number_format($cierre->compras_contado, 2, ',', '.') }}
                                </small>
                                <small class="d-block text-muted">
                                    Crédito: Bs. {{ number_format($cierre->compras_credito, 2, ',', '.') }}
                                </small>
                            </td>
                            <td class="text-end">
                                <strong>
                                    <span class="text-muted">Bs.</span> 
                                    {{ number_format($cierre->saldo_final, 2, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <i class="fas fa-user text-secondary me-1"></i>
                                {{ $cierre->usuario->name ?? 'N/A' }}
                            </td>
                            <td class="text-center">
                                @if($cierre->estado == 'completado')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Completado
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('cierre.show', $cierre->id) }}" 
                                       class="btn btn-sm btn-info" 
                                       data-bs-toggle="tooltip" 
                                       title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($cierre->estado == 'pendiente')
                                        <a href="{{ route('cierre.edit', $cierre->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                No hay cierres registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $cierres->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush

@endsection
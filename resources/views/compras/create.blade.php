@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0"><i class="fas fa-shopping-cart"></i> Nueva Compra</h1>
            <a href="{{ route('compras.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="card-body">
            <form id="compraForm" action="{{ route('compras.store') }}" method="POST">
                @csrf
                
                <!-- Información General -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="proveedor_id" class="form-label">Proveedor:</label>
                                    <select class="form-select" id="proveedor_id" name="proveedor_id" required>
                                        <option value="">Seleccione un proveedor</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="tipo" class="form-label">Tipo de Compra:</label>
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <option value="contado">Contado</option>
                                        <option value="credito">Crédito</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="fecha" class="form-label">Fecha:</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Campos de Pago -->
                <div id="pagoContadoFields" class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-money-bill"></i> Información de Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago:</label>
                                    <select class="form-select" id="tipo_pago" name="tipo_pago">
                                        <option value="">Seleccione tipo de pago</option>
                                        <option value="transferencia">Transferencia</option>
                                        <option value="efectivo">Efectivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="cuenta_bancaria_id" class="form-label">Cuenta Bancaria:</label>
                                    <select class="form-select" id="cuenta_bancaria_id" name="cuenta_bancaria_id">
                                        <option value="">Seleccione cuenta</option>
                                        @foreach($cuentasBancarias as $cuenta)
                                            <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }} - {{ $cuenta->numero_cuenta }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ítems de Compra -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Ítems de Compra</h5>
                        <button type="button" id="addItemBtn" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Agregar Ítem
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%">Tipo</th>
                                        <th style="width: 30%">Item</th>
                                        <th style="width: 15%">Cantidad</th>
                                        <th style="width: 20%">Precio Unitario</th>
                                        <th style="width: 15%">Subtotal</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer">
                                    <tr class="item-row">
                                        <td>
                                            <select class="form-select tipo-item" name="items[0][tipo_item]" required>
                                                <option value="">Seleccione tipo</option>
                                                <option value="materia_prima">Materia Prima</option>
                                                <option value="producto">Producto</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select item-id" name="items[0][item_id]" required>
                                                <option value="">Seleccione item</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0.01" class="form-control cantidad" name="items[0][cantidad]" placeholder="Cantidad" required>
                                                <span class="input-group-text unidad-medida">unidades</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text">Bs</span>
                                                <input type="number" step="0.01" min="0" class="form-control precio" name="items[0][precio_unitario]" placeholder="Precio" required>
                                            </div>
                                        </td>
                                        <td class="text-end subtotal">Bs 0.00</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold" id="totalCompra">Bs 0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Compra
                    </button>
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    let itemCount = 1;

    const materiasPrimas = @json($materiasPrimas);
    const productos = @json($productos);

    function calcularSubtotal(row) {
        const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.precio').value) || 0;
        const subtotal = cantidad * precio;
        row.querySelector('.subtotal').textContent = `Bs ${subtotal.toFixed(2)}`;
        calcularTotal();
    }

    function calcularTotal() {
        const subtotales = document.querySelectorAll('.subtotal');
        let total = 0;
        subtotales.forEach(subtotal => {
            total += parseFloat(subtotal.textContent.replace('Bs ', '')) || 0;
        });
        document.getElementById('totalCompra').textContent = `Bs ${total.toFixed(2)}`;
    }

    addItemBtn.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select class="form-select tipo-item" name="items[${itemCount}][tipo_item]" required>
                    <option value="">Seleccione tipo</option>
                    <option value="materia_prima">Materia Prima</option>
                    <option value="producto">Producto</option>
                </select>
            </td>
            <td>
                <select class="form-select item-id" name="items[${itemCount}][item_id]" required>
                    <option value="">Seleccione item</option>
                </select>
            </td>
            <td>
                <div class="input-group">
                    <input type="number" step="0.01" min="0.01" class="form-control cantidad" name="items[${itemCount}][cantidad]" placeholder="Cantidad" required>
                    <span class="input-group-text unidad-medida">unidades</span>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Bs</span>
                    <input type="number" step="0.01" min="0" class="form-control precio" name="items[${itemCount}][precio_unitario]" placeholder="Precio" required>
                </div>
            </td>
            <td class="text-end subtotal">Bs 0.00</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        itemsContainer.appendChild(newRow);
        itemCount++;
        initItemEvents(newRow);
    });

    function initItemEvents(row) {
        const tipoSelect = row.querySelector('.tipo-item');
        const itemSelect = row.querySelector('.item-id');
        const unidadMedidaSpan = row.querySelector('.unidad-medida');
        const cantidadInput = row.querySelector('.cantidad');
        const precioInput = row.querySelector('.precio');
        const removeBtn = row.querySelector('.remove-item');

        tipoSelect.addEventListener('change', function() {
            itemSelect.innerHTML = '<option value="">Seleccione item</option>';
            itemSelect.disabled = !this.value;
            unidadMedidaSpan.textContent = 'unidades';

            const opciones = this.value === 'materia_prima' ? materiasPrimas : productos;

            opciones.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nombre;
                option.setAttribute('data-unidad', item.unidad_medida || 'unidades');
                itemSelect.appendChild(option);
            });
        });

        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.value) {
                unidadMedidaSpan.textContent = selectedOption.getAttribute('data-unidad') || 'unidades';
            } else {
                unidadMedidaSpan.textContent = 'unidades';
            }
        });

        cantidadInput.addEventListener('input', () => calcularSubtotal(row));
        precioInput.addEventListener('input', () => calcularSubtotal(row));

        removeBtn.addEventListener('click', function() {
            row.remove();
            calcularTotal();
        });
    }

    // Inicializar eventos existentes
    document.querySelectorAll('.item-row').forEach(row => {
        initItemEvents(row);
    });

    // Mostrar/ocultar campos de pago al contado
    const tipoSelect = document.getElementById('tipo');
    const pagoContadoFields = document.getElementById('pagoContadoFields');
    const tipoPagoSelect = document.getElementById('tipo_pago');
    const cuentaBancariaSelect = document.getElementById('cuenta_bancaria_id');

    function togglePagoContadoFields() {
        if (tipoSelect.value === 'contado') {
            pagoContadoFields.style.display = 'block';
            tipoPagoSelect.required = true;
            cuentaBancariaSelect.required = true;
        } else {
            pagoContadoFields.style.display = 'none';
            tipoPagoSelect.required = false;
            cuentaBancariaSelect.required = false;
        }
    }

    tipoSelect.addEventListener('change', togglePagoContadoFields);
    togglePagoContadoFields(); // Ejecutar al cargar la página
});
</script>
@endsection

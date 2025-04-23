<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Proveedor;
use App\Models\MateriaPrimaSinFiltrar;
use App\Models\Producto;
use App\Models\CuentaBancaria;
use App\Models\PagoProveedor;
use App\Models\MovimientoBancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['proveedor', 'items'])->get();
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        return view('compras.create', [
            'proveedores' => Proveedor::all(),
            'materiasPrimas' => MateriaPrimaSinFiltrar::all(),
            'productos' => Producto::all(),
            'cuentasBancarias' => CuentaBancaria::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompra($request);

        DB::transaction(function () use ($validated) {
            $compra = Compra::create([
                'proveedor_id' => $validated['proveedor_id'],
                'tipo' => $validated['tipo'],
                'fecha' => $validated['fecha'],
                'total' => 0,
                'pagada' => $validated['tipo'] == 'contado'
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $subtotal;

                CompraItem::create([
                    'compra_id' => $compra->id,
                    'tipo_item' => $item['tipo_item'],
                    'materia_prima_id' => $item['tipo_item'] === 'materia_prima' ? $item['item_id'] : null,
                    'producto_id' => $item['tipo_item'] === 'producto' ? $item['item_id'] : null,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                $this->actualizarStock($item, 'sumar');
            }

            $compra->update(['total' => $total]);

            // Si es pago al contado, registrar el pago
            if ($validated['tipo'] == 'contado') {
                PagoProveedor::create([
                    'compra_id' => $compra->id,
                    'cuenta_bancaria_id' => $validated['cuenta_bancaria_id'],
                    'metodo_pago' => $validated['tipo_pago'],
                    'monto' => $total,
                    'fecha_pago' => $validated['fecha']
                ]);

                // Registrar movimiento bancario
                MovimientoBancario::create([
                    'cuenta_bancaria_id' => $validated['cuenta_bancaria_id'],
                    'tipo' => 'egreso',
                    'monto' => $total,
                    'fecha' => $validated['fecha'],
                    'concepto' => 'Pago de compra #' . $compra->id,
                    'movimiento_type' => 'App\\Models\\Compra',
                    'movimiento_id' => $compra->id
                ]);
            }
        });

        // Limpiar todo el caché después de la operación
        Cache::flush();

        return redirect()->route('compras.index')->with('success', 'Compra registrada correctamente');
    }

    public function show(Compra $compra)
    {
        $compra->load('items', 'proveedor');
        return view('compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        $compra->load(['items', 'pagos']);
        return view('compras.edit', [
            'compra' => $compra,
            'proveedores' => Proveedor::all(),
            'materiasPrimas' => MateriaPrimaSinFiltrar::all(),
            'productos' => Producto::all(),
            'cuentasBancarias' => CuentaBancaria::all()
        ]);
    }

    public function update(Request $request, Compra $compra)
    {
        $validated = $this->validateCompra($request);

        DB::transaction(function () use ($validated, $compra) {
            // Revertir stock anterior
            foreach ($compra->items as $oldItem) {
                $this->actualizarStock([
                    'tipo_item' => $oldItem->tipo_item,
                    'item_id' => $oldItem->tipo_item === 'materia_prima' ? $oldItem->materia_prima_id : $oldItem->producto_id,
                    'cantidad' => $oldItem->cantidad
                ], 'restar');
            }

            // Eliminar ítems anteriores
            $compra->items()->delete();

            // Actualizar datos de la compra
            $compra->update([
                'proveedor_id' => $validated['proveedor_id'],
                'tipo' => $validated['tipo'],
                'fecha' => $validated['fecha'],
                'total' => 0 // recalculado
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $subtotal;

                CompraItem::create([
                    'compra_id' => $compra->id,
                    'tipo_item' => $item['tipo_item'],
                    'materia_prima_id' => $item['tipo_item'] === 'materia_prima' ? $item['item_id'] : null,
                    'producto_id' => $item['tipo_item'] === 'producto' ? $item['item_id'] : null,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                $this->actualizarStock($item, 'sumar');
            }

            $compra->update(['total' => $total]);
        });

        return redirect()->route('compras.index')->with('success', 'Compra actualizada correctamente');
    }

    public function destroy(Compra $compra)
    {
        DB::transaction(function () use ($compra) {
            // Revertir stock antes de eliminar
            foreach ($compra->items as $item) {
                $this->actualizarStock([
                    'tipo_item' => $item->tipo_item,
                    'item_id' => $item->tipo_item === 'materia_prima' ? $item->materia_prima_id : $item->producto_id,
                    'cantidad' => $item->cantidad
                ], 'restar');
            }

            $compra->items()->delete();
            $compra->delete();
        });

        return redirect()->route('compras.index')->with('success', 'Compra eliminada correctamente');
    }

    private function validateCompra(Request $request)
    {
        $rules = [
            'proveedor_id' => 'required|exists:proveedores,id',
            'tipo' => 'required|in:contado,credito',
            'fecha' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.tipo_item' => 'required|in:materia_prima,producto',
            'items.*.item_id' => 'required|integer',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ];

        // Solo requerir campos de pago si es al contado
        if ($request->tipo === 'contado') {
            $rules['tipo_pago'] = 'required|in:transferencia,efectivo';
            $rules['cuenta_bancaria_id'] = 'required|exists:cuentas_bancarias,id';
        }

        return $request->validate($rules);
    }

    private function actualizarStock(array $item, string $accion = 'sumar')
    {
        $cantidad = $accion === 'sumar' ? $item['cantidad'] : -$item['cantidad'];

        if ($item['tipo_item'] === 'materia_prima') {
            $materia = MateriaPrimaSinFiltrar::findOrFail($item['item_id']);
            $materia->increment('stock', $cantidad);
        } else {
            $producto = Producto::findOrFail($item['item_id']);
            $stockAnterior = $producto->stock;
            $producto->stock = $stockAnterior + $cantidad;
            $producto->save();
            \Log::info("Stock de producto actualizado: {$producto->nombre}, anterior: {$stockAnterior}, cantidad: {$cantidad}, nuevo: {$producto->stock}");
        }
    }
}

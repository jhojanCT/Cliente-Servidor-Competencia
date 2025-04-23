<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Cliente;
use App\Models\MateriaPrimaFiltrada;
use App\Models\Producto;
use App\Models\CuentaBancaria;
use App\Models\PagoCliente;
use App\Models\MovimientoBancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver-ventas')->only('index', 'show');
        $this->middleware('can:crear-ventas')->only(['create', 'store']);
        $this->middleware('can:editar-ventas')->only(['edit', 'update']);
        $this->middleware('can:eliminar-ventas')->only('destroy');
    }

    public function index()
    {
        $ventas = Venta::with(['cliente', 'items'])->orderBy('fecha', 'desc')->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $materiasPrimas = MateriaPrimaFiltrada::where('stock', '>', 0)->get();
        $productos = Producto::where('stock', '>', 0)->get();
        $cuentasBancarias = CuentaBancaria::all();
        return view('ventas.create', compact('clientes', 'materiasPrimas', 'productos', 'cuentasBancarias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo' => 'required|in:contado,credito',
            'fecha' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.tipo_item' => 'required|in:materia_prima_filtrada,producto',
            'items.*.item_id' => 'required|integer',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'tipo' => $request->tipo,
                'fecha' => $request->fecha,
                'total' => 0,
                'pagada' => $request->tipo == 'contado'
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $subtotal;

                VentaItem::create([
                    'venta_id' => $venta->id,
                    'tipo_item' => $item['tipo_item'],
                    'materia_prima_filtrada_id' => $item['tipo_item'] == 'materia_prima_filtrada' ? $item['item_id'] : null,
                    'producto_id' => $item['tipo_item'] == 'producto' ? $item['item_id'] : null,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal
                ]);

                // Reducir stock
                if ($item['tipo_item'] == 'materia_prima_filtrada') {
                    $materia = MateriaPrimaFiltrada::findOrFail($item['item_id']);
                    if ($materia->stock < $item['cantidad']) {
                        throw new \Exception("No hay suficiente stock de {$materia->nombre}");
                    }
                    $materia->decrement('stock', $item['cantidad']);
                } else {
                    $producto = Producto::findOrFail($item['item_id']);
                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception("No hay suficiente stock de {$producto->nombre}");
                    }
                    $producto->decrement('stock', $item['cantidad']);
                }
            }

            // Actualizar total de la venta
            $venta->update(['total' => $total]);

            // Si es pago al contado, registrar el pago
            if ($request->tipo === 'contado') {
                PagoCliente::create([
                    'venta_id' => $venta->id,
                    'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                    'metodo_pago' => $request->tipo_pago,
                    'monto' => $total,
                    'fecha_pago' => $request->fecha
                ]);

                // Registrar movimiento bancario
                MovimientoBancario::create([
                    'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                    'tipo' => 'ingreso',
                    'monto' => $total,
                    'fecha' => $request->fecha,
                    'concepto' => 'Pago de venta #' . $venta->id,
                    'movimiento_type' => 'App\\Models\\Venta',
                    'movimiento_id' => $venta->id
                ]);
            }
        });

        // Limpiar todo el caché después de la operación
        Cache::flush();

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente');
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'items.materiaPrimaFiltrada', 'items.producto', 'pagos']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        if ($venta->fecha < now()->subDays(30)) {
            return redirect()->route('ventas.index')->with('error', 'No se pueden editar ventas con más de 30 días');
        }

        $clientes = Cliente::all();
        $materiasPrimas = MateriaPrimaFiltrada::all();
        $productos = Producto::all();
        
        $venta->load('items');
        
        return view('ventas.edit', compact('venta', 'clientes', 'materiasPrimas', 'productos'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo' => 'required|in:contado,credito',
            'fecha' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.tipo_item' => 'required|in:materia_prima_filtrada,producto',
            'items.*.item_id' => 'required|integer',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $venta) {
            // Revertir stock de los items anteriores
            foreach ($venta->items as $oldItem) {
                if ($oldItem->tipo_item == 'materia_prima_filtrada') {
                    MateriaPrimaFiltrada::find($oldItem->materia_prima_filtrada_id)
                        ->increment('stock', $oldItem->cantidad);
                } else {
                    Producto::find($oldItem->producto_id)
                        ->increment('stock', $oldItem->cantidad);
                }
            }

            // Eliminar items anteriores
            $venta->items()->delete();

            // Actualizar datos de la venta
            $venta->update([
                'cliente_id' => $request->cliente_id,
                'tipo' => $request->tipo,
                'fecha' => $request->fecha,
                'total' => 0,
                'pagada' => $request->tipo == 'contado' ? true : false
            ]);

            $total = 0;

            // Procesar nuevos items
            foreach ($request->items as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $subtotal;

                VentaItem::create([
                    'venta_id' => $venta->id,
                    'tipo_item' => $item['tipo_item'],
                    'materia_prima_filtrada_id' => $item['tipo_item'] == 'materia_prima_filtrada' ? $item['item_id'] : null,
                    'producto_id' => $item['tipo_item'] == 'producto' ? $item['item_id'] : null,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal
                ]);

                // Reducir stock
                if ($item['tipo_item'] == 'materia_prima_filtrada') {
                    $materia = MateriaPrimaFiltrada::findOrFail($item['item_id']);
                    if ($materia->stock < $item['cantidad']) {
                        throw new \Exception("No hay suficiente stock de {$materia->nombre}");
                    }
                    $materia->decrement('stock', $item['cantidad']);
                } else {
                    $producto = Producto::findOrFail($item['item_id']);
                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception("No hay suficiente stock de {$producto->nombre}");
                    }
                    $producto->decrement('stock', $item['cantidad']);
                }
            }

            $venta->update(['total' => $total]);
        });

        return redirect()->route('ventas.show', $venta)->with('success', 'Venta actualizada correctamente');
    }

    public function destroy(Venta $venta)
    {
        DB::transaction(function () use ($venta) {
            // Revertir stock
            foreach ($venta->items as $item) {
                if ($item->tipo_item == 'materia_prima_filtrada') {
                    MateriaPrimaFiltrada::find($item->materia_prima_filtrada_id)
                        ->increment('stock', $item->cantidad);
                } else {
                    Producto::find($item->producto_id)
                        ->increment('stock', $item->cantidad);
                }
            }

            // Eliminar items y pagos asociados
            $venta->items()->delete();
            $venta->pagos()->delete();
            $venta->delete();
        });

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente');
    }
}
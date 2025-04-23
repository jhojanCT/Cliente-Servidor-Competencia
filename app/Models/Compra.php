<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $casts = [
        'fecha' => 'date',
    ];

    protected $fillable = [
        'proveedor_id',
        'tipo',
        'fecha',
        'total',
        'pagada'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function items()
    {
        return $this->hasMany(CompraItem::class);
    }

    public function movimientosBancarios()
    {
        return $this->morphMany(MovimientoBancario::class, 'movimiento');
    }
    public function pagos()
    {
        return $this->hasMany(PagoProveedor::class);
    }
    
    public function saldoPendiente()
    {
        return $this->total - $this->pagos()->sum('monto');
    }
}
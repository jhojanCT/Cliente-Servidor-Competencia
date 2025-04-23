<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    use HasFactory;
    protected $casts = [
        'fecha' => 'datetime',
    ];
    

    protected $fillable = [
        'cliente_id',
        'tipo',
        'fecha',
        'total',
        'pagada'
    ];

    protected $attributes = [
        'pagada' => false
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items()
    {
        return $this->hasMany(VentaItem::class);
    }

    public function movimientosBancarios()
    {
        return $this->morphMany(MovimientoBancario::class, 'movimiento');
    }
    // En App\Models\Venta.php
    public function pagos()
    {
        return $this->hasMany(PagoCliente::class);
    }
    
    public function saldoPendiente()
    {
        return $this->total - $this->pagos()->sum('monto');
    }
}
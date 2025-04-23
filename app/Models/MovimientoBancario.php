<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoBancario extends Model
{
    use HasFactory;
    protected $table = 'movimientos_bancarios';
    protected $casts = [
        'fecha' => 'date',
    ];
    

    protected $fillable = [
        'cuenta_bancaria_id',
        'movimiento_type',
        'movimiento_id',
        'tipo',
        'concepto',
        'monto',
        'saldo_actual',
        'fecha'
    ];

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class);
    }

    public function movimiento()
    {
        return $this->morphTo();
    }
}
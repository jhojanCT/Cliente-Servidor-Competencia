<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCliente extends Model
{
    use HasFactory;

    protected $table = 'pagos_clientes';
    protected $casts = [
        'fecha_pago' => 'datetime',
    ];
    

    protected $fillable = [
        'venta_id',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'cuenta_bancaria_id',
        'comprobante'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class);
    }
}
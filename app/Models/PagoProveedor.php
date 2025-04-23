<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoProveedor extends Model
{
    use HasFactory;
    protected $table = 'pagos_proveedores';
    protected $casts = [
        'fecha_pago' => 'datetime',
    ];

    protected $fillable = [
        'compra_id',
        'monto',
        'fecha_pago',
        'metodo_pago', // efectivo, transferencia, etc.
        'cuenta_bancaria_id', // Si aplica
        'comprobante' // Número de factura o referencia
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class);
    }

    public function movimientosBancarios()
    {
        return $this->morphMany(MovimientoBancario::class, 'movimiento');
    }
}
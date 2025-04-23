<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    use HasFactory;
    protected $table = 'cuentas_bancarias';

    protected $fillable = [
        'nombre_banco',
        'numero_cuenta',
        'saldo'
    ];

    public function movimientosBancarios()
    {
        return $this->hasMany(MovimientoBancario::class);
    }
    
}
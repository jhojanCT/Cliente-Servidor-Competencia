<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreDiario extends Model
{
    use HasFactory;
    protected $table = 'cierres_diarios';
    protected $casts = [
        'fecha' => 'date',
    ];

    protected $fillable = [
        'fecha',
        'saldo_inicial',
        'ventas_contado',
        'ventas_credito',
        'compras_contado',
        'compras_credito',
        'saldo_final',
        'cerrado',
        'usuario_id',
        'pagos_clientes',
        'pagos_proveedores',
        'ingresos_bancarios',
        'egresos_bancarios',
        'observaciones'
    ];

    // app/Models/CierreDiario.php

public function usuario()
{
    return $this->belongsTo(User::class, 'usuario_id'); // O 'user_id' si así se llama tu campo
}

}
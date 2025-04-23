<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;
    protected $casts = [
        'fecha' => 'date',
    ];
    protected $table = 'producciones';

    protected $fillable = [
        'materia_prima_filtrada_id',
        'producto_id',
        'cantidad_utilizada',
        'cantidad_producida',
        'costo_produccion',
        'fecha'
    ];

    public function materiaPrimaFiltrada()
    {
        return $this->belongsTo(MateriaPrimaFiltrada::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
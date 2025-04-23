<?php

namespace App\Observers;

use App\Models\MovimientoBancario;
use App\Models\CuentaBancaria;
use Illuminate\Support\Facades\Log;

class MovimientoBancarioObserver
{
    /**
     * Handle the MovimientoBancario "creating" event.
     */
    public function creating(MovimientoBancario $movimientoBancario): void
    {
        Log::info('Observer: creating movimiento bancario', [
            'cuenta_id' => $movimientoBancario->cuenta_bancaria_id,
            'tipo' => $movimientoBancario->tipo,
            'monto' => $movimientoBancario->monto
        ]);

        // Obtener la cuenta bancaria
        $cuentaBancaria = CuentaBancaria::find($movimientoBancario->cuenta_bancaria_id);
        
        if ($cuentaBancaria) {
            // Usar el saldo actual de la cuenta como base
            $saldoAnterior = $cuentaBancaria->saldo;
            
            // Calcular el nuevo saldo
            $movimientoBancario->saldo_actual = $movimientoBancario->tipo === 'ingreso' 
                ? $saldoAnterior + $movimientoBancario->monto 
                : $saldoAnterior - $movimientoBancario->monto;

            Log::info('Observer: saldo calculado', [
                'saldo_anterior' => $saldoAnterior,
                'saldo_actual' => $movimientoBancario->saldo_actual
            ]);

            // Actualizar el saldo en la cuenta bancaria
            $cuentaBancaria->saldo = $movimientoBancario->saldo_actual;
            $cuentaBancaria->save();
            
            Log::info('Observer: cuenta bancaria actualizada', [
                'cuenta_id' => $cuentaBancaria->id,
                'nuevo_saldo' => $cuentaBancaria->saldo
            ]);
        }
    }

    /**
     * Handle the MovimientoBancario "updating" event.
     */
    public function updating(MovimientoBancario $movimientoBancario): void
    {
        // Si se actualiza el monto o el tipo, recalcular el saldo
        if ($movimientoBancario->isDirty(['monto', 'tipo'])) {
            $cuentaBancaria = CuentaBancaria::find($movimientoBancario->cuenta_bancaria_id);
            
            if ($cuentaBancaria) {
                // Obtener el saldo anterior al movimiento actual
                $saldoAnterior = $cuentaBancaria->saldo;
                
                // Ajustar el saldo anterior restando el monto actual del movimiento
                if ($movimientoBancario->getOriginal('tipo') === 'ingreso') {
                    $saldoAnterior -= $movimientoBancario->getOriginal('monto');
                } else {
                    $saldoAnterior += $movimientoBancario->getOriginal('monto');
                }
                
                // Calcular el nuevo saldo con los valores actualizados
                $movimientoBancario->saldo_actual = $movimientoBancario->tipo === 'ingreso' 
                    ? $saldoAnterior + $movimientoBancario->monto 
                    : $saldoAnterior - $movimientoBancario->monto;

                // Actualizar el saldo en la cuenta bancaria
                $cuentaBancaria->saldo = $movimientoBancario->saldo_actual;
                $cuentaBancaria->save();
            }
        }
    }

    /**
     * Handle the MovimientoBancario "deleted" event.
     */
    public function deleted(MovimientoBancario $movimientoBancario): void
    {
        $cuentaBancaria = CuentaBancaria::find($movimientoBancario->cuenta_bancaria_id);
        
        if ($cuentaBancaria) {
            // Ajustar el saldo de la cuenta restando el monto del movimiento eliminado
            if ($movimientoBancario->tipo === 'ingreso') {
                $cuentaBancaria->saldo -= $movimientoBancario->monto;
            } else {
                $cuentaBancaria->saldo += $movimientoBancario->monto;
            }
            
            $cuentaBancaria->save();
        }
    }

    /**
     * Handle the MovimientoBancario "restored" event.
     */
    public function restored(MovimientoBancario $movimientoBancario): void
    {
        //
    }

    /**
     * Handle the MovimientoBancario "force deleted" event.
     */
    public function forceDeleted(MovimientoBancario $movimientoBancario): void
    {
        //
    }
}

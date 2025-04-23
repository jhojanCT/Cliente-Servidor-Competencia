<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cierres_diarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->date('fecha');
            $table->decimal('saldo_inicial', 12, 2);
            $table->decimal('ventas_contado', 12, 2)->default(0);
            $table->decimal('ventas_credito', 12, 2)->default(0);
            $table->decimal('compras_contado', 12, 2)->default(0);
            $table->decimal('compras_credito', 12, 2)->default(0);
            $table->decimal('pagos_clientes', 12, 2)->default(0);
            $table->decimal('pagos_proveedores', 12, 2)->default(0);
            $table->decimal('ingresos_bancarios', 12, 2)->default(0);
            $table->decimal('egresos_bancarios', 12, 2)->default(0);
            $table->decimal('saldo_final', 12, 2);
            $table->text('observaciones')->nullable();
            $table->boolean('cerrado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierres_diarios');
    }
};

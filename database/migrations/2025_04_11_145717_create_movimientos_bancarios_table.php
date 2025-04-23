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
        Schema::create('movimientos_bancarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_bancaria_id')->constrained('cuentas_bancarias');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->string('concepto');
            $table->decimal('monto', 12, 2);
            $table->decimal('saldo_actual', 12, 2)->nullable();
            $table->date('fecha');
            $table->string('movimiento_type')->nullable();
            $table->unsignedBigInteger('movimiento_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_bancarios');
    }
};

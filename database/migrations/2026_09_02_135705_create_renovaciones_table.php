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
        Schema::create('renovaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')
                  ->comment('Venta que se renueva')
                  ->constrained('ventas')
                  ->cascadeOnDelete();
            $table->foreignId('usuario_sistema_id')
                  ->constrained('usuarios_sistema');
            $table->enum('tipo', ['misma_cuenta', 'nueva_cuenta']);
            $table->foreignId('cuenta_anterior_id')->nullable()->constrained('cuentas');
            $table->foreignId('cuenta_nueva_id')->nullable()->constrained('cuentas');
            $table->timestamp('fecha_renovacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renovaciones');
    }
};

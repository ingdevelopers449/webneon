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
        Schema::create('comprobantes_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                  ->comment('Cliente del sistema al que se extiende')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->foreignId('registrado_por_admin_id')
                  ->constrained('usuarios_sistema');
            $table->string('archivo_ruta', 500);
            $table->integer('dias_asignados');
            $table->string('nota', 255)->nullable();
            $table->timestamp('fecha_carga')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes_pago');
    }
};

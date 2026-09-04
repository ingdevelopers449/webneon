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
            $table->foreignId('suscripcion_id')
                  ->constrained('suscripciones')
                  ->cascadeOnDelete();
            $table->string('nombre_archivo', 255);
            $table->string('ruta_archivo', 500);
            $table->timestamp('fecha_carga')->useCurrent();
            $table->foreignId('administrador_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
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

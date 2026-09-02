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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->string('dispositivo', 255)->nullable();
            $table->string('direccion_ip', 45)->nullable();
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->dateTime('ultima_actividad')->nullable();
            $table->boolean('activa')->default(true);
            $table->dateTime('fecha_cierre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};

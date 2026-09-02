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
        Schema::create('usuarios_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('correo', 150)->unique();
            $table->string('password_hash', 255)->comment('Hash irreversible (RNF-01)');
            $table->string('telefono', 20)->nullable();
            $table->enum('rol', ['administrador', 'cliente']);
            $table->enum('estado_cuenta', ['activo', 'desactivado', 'bloqueado', 'suspendido'])
                  ->default('activo');
            $table->boolean('demo_utilizada')->default(false)->comment('RN-01: demo única por usuario');
            $table->enum('tipo_periodo_actual', ['demo', 'suscripcion'])->default('demo');
            $table->dateTime('fecha_inicio_periodo')->comment('RN-02: inicia al completar el registro');
            $table->dateTime('fecha_fin_periodo');
            $table->enum('estado_suscripcion', ['activa', 'vencida', 'cancelada'])->default('activa');
            $table->boolean('cancelacion_solicitada')->default(false);
            $table->dateTime('fecha_solicitud_cancelacion')->nullable();
            $table->boolean('suspension_inmediata')->default(false)->comment('Decisión del Administrador (RF-11)');
            $table->string('moneda', 10)->default('COP');
            $table->timestamp('fecha_registro')->useCurrent();

            $table->index(['estado_cuenta', 'estado_suscripcion'], 'idx_usuarios_estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_sistema');
    }
};

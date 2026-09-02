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
        Schema::create('log_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('usuarios_sistema')
                  ->nullOnDelete();
            $table->string('correo_intentado', 150)->nullable()->comment('Para intentos fallidos de login');
            $table->string('accion', 150);
            $table->text('detalle')->nullable();
            $table->string('direccion_ip', 45)->nullable();
            $table->enum('resultado', ['exitoso', 'fallido'])->default('exitoso');
            $table->dateTime('fecha_hora')->useCurrent();

            $table->index('fecha_hora', 'idx_auditoria_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_auditoria');
    }
};

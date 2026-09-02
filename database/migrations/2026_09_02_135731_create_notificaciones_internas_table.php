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
        Schema::create('notificaciones_internas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_sistema_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->enum('tipo', ['cuenta_por_vencer', 'cliente_por_vencer', 'suscripcion_por_vencer']);
            $table->unsignedBigInteger('referencia_id')
                  ->nullable()
                  ->comment('ID de la cuenta, venta o suscripción referida');
            $table->string('mensaje', 255);
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_generacion')->useCurrent();

            $table->index(['usuario_sistema_id', 'leida'], 'idx_notificaciones_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_internas');
    }
};

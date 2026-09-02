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
        Schema::create('clientes_finales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_sistema_id')
                  ->comment('Tenant: Cliente del sistema dueño del registro')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->string('nombre', 150);
            $table->string('telefono', 20);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->boolean('eliminado')->default(false)->comment('Eliminación lógica');
            $table->timestamp('fecha_registro')->useCurrent();

            $table->unique(['usuario_sistema_id', 'telefono'], 'uq_cliente_telefono_tenant');
            $table->index('usuario_sistema_id', 'idx_clientes_tenant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_finales');
    }
};

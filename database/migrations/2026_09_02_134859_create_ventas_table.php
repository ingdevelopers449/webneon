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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_sistema_id')
                  ->comment('Tenant: dueño del negocio')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->foreignId('cliente_final_id')
                  ->constrained('clientes_finales')
                  ->cascadeOnDelete();
            $table->enum('tipo_venta', ['perfil', 'cuenta_completa', 'individual', 'combo']);
            $table->foreignId('plataforma_id')->nullable()->constrained('plataformas');
            $table->foreignId('cuenta_id')->nullable()->constrained('cuentas');
            $table->foreignId('perfil_id')->nullable()->constrained('perfiles');
            $table->foreignId('combo_id')->nullable()->constrained('combos');
            $table->decimal('precio', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento')->comment('RN-10: no debería superar la de la cuenta origen');
            $table->enum('estado', ['activa', 'vencida', 'renovada', 'cancelada'])->default('activa');
            $table->boolean('es_venta_rapida')->default(false);
            $table->timestamp('fecha_registro')->useCurrent();

            $table->index(['fecha_vencimiento', 'estado'], 'idx_ventas_vencimiento');
            $table->index('usuario_sistema_id', 'idx_ventas_tenant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

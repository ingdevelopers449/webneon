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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plataforma_id')
                  ->constrained('plataformas')
                  ->cascadeOnDelete();
            $table->foreignId('proveedor_id')
                  ->nullable()
                  ->constrained('proveedores')
                  ->nullOnDelete();
            $table->string('correo', 150);
            $table->string('contrasena', 255)->comment('Credencial protegida (RNF-01, RN-16)');
            $table->date('fecha_vencimiento');
            $table->decimal('costo_compra', 10, 2);
            $table->enum('estado', [
                'disponible',
                'parcialmente_ocupada',
                'ocupada',
                'proxima_a_vencer',
                'vencida',
                'desactivada',
            ])->default('disponible');
            $table->timestamp('fecha_registro')->useCurrent();

            $table->index('estado', 'idx_cuentas_estado');
            $table->index('fecha_vencimiento', 'idx_cuentas_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};

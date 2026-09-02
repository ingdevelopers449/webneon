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
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id')
                  ->constrained('cuentas')
                  ->cascadeOnDelete();
            $table->string('nombre_perfil', 50);
            $table->string('pin', 10)->nullable();
            $table->enum('estado', ['disponible', 'ocupado'])->default('disponible');
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->unique(['cuenta_id', 'nombre_perfil'], 'uq_perfil_por_cuenta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfiles');
    }
};

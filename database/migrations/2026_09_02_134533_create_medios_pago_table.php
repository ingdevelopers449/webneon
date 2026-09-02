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
        Schema::create('medios_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_sistema_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('detalle', 255)->nullable();
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medios_pago');
    }
};

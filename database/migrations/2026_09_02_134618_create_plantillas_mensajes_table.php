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
        Schema::create('plantillas_mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_sistema_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->enum('tipo', ['entrega', 'proximo_vencimiento', 'renovacion']);
            $table->text('contenido')->comment('Incluye variables dinámicas {{nombre}}, {{plataforma}}, etc.');
            $table->foreignId('medio_pago_id')
                  ->nullable()
                  ->constrained('medios_pago')
                  ->nullOnDelete();
            $table->boolean('predeterminada')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantillas_mensajes');
    }
};

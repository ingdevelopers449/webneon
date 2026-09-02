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
        Schema::create('plataformas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_sistema_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->enum('tipo_comercializacion', ['perfil', 'cuenta_completa', 'individual']);
            $table->boolean('requiere_pin')->default(false);
            $table->unsignedInteger('capacidad_maxima_perfiles')
                  ->nullable()
                  ->comment('Solo aplica si tipo = perfil');
            $table->decimal('precio_perfil', 10, 2)->nullable();
            $table->decimal('precio_cuenta_completa', 10, 2)->nullable();
            $table->decimal('precio_individual', 10, 2)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamp('fecha_registro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plataformas');
    }
};

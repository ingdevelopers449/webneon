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
        Schema::create('configuracion_contacto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')
                  ->unique()
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->string('whatsapp', 20)->nullable();
            $table->string('correo_contacto', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_contacto');
    }
};

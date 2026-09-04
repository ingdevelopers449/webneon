<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_soporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('asunto');
            $table->text('descripcion');
            $table->enum('estado', ['abierto', 'en_proceso', 'resuelto'])->default('abierto');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_soporte');
    }
};

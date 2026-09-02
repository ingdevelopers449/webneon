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
        Schema::create('tokens_recuperacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                  ->constrained('usuarios_sistema')
                  ->cascadeOnDelete();
            $table->string('token', 255)->unique();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->dateTime('fecha_expiracion')->comment('Vigencia máxima de 5 minutos');
            $table->boolean('usado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens_recuperacion');
    }
};

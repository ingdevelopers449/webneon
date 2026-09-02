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
        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')
                  ->constrained('combos')
                  ->cascadeOnDelete();
            $table->foreignId('plataforma_id')
                  ->constrained('plataformas');
            $table->enum('tipo_venta', ['perfil', 'cuenta_completa', 'individual']);
            $table->unsignedInteger('cantidad')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_items');
    }
};

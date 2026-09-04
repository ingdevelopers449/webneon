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
        Schema::table('suscripciones', function (Blueprint $table) {
            // Referencia al plan de suscripción del catálogo
            $table->foreignId('plan_id')->nullable()->constrained('planes_suscripcion')->nullOnDelete()->after('usuario_id');
            // Precio y tipo guardados al momento de crear la suscripción
            // (histórico, por si el plan cambia de precio en el futuro)
            $table->decimal('precio', 10, 2)->nullable()->after('plan_id');
            $table->string('tipo_suscripcion')->nullable()->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'precio', 'tipo_suscripcion']);
        });
    }
};

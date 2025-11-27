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
        Schema::create('caja_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cierre_id')->constrained('cierre_cajas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('accion'); // ej. 'pausa', 'reapertura', 'cierre'
            $table->text('detalles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_logs');
    }
};

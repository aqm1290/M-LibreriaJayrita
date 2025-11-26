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
        Schema::create('cierre_cajas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique(); 
            $table->foreignId('usuario_id')->constrained('users');
            $table->decimal('monto_apertura', 12, 2)->default(0);
            $table->decimal('total_efectivo', 12, 2)->nullable();
            $table->decimal('total_qr', 12, 2)->nullable();
            $table->decimal('total_ventas', 12, 2)->nullable();
            $table->integer('cantidad_ventas')->default(0);
            $table->decimal('monto_cierre_fisico', 12, 2)->nullable(); 
            $table->decimal('diferencia', 12, 2)->nullable(); 
            $table->text('observaciones')->nullable();
            $table->string('reporte_pdf')->nullable();
            $table->boolean('caja_abierta')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_cajas');
    }
};

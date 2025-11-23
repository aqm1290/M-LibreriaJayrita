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
        Schema::create('cierre_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha')->unique(); // Un cierre por día
            $table->decimal('monto_apertura', 10, 2)->default(0.00); // ← YA INCLUIDO
            $table->decimal('total_efectivo', 10, 2)->default(0.00);
            $table->decimal('total_qr', 10, 2)->default(0.00);
            $table->decimal('total_ventas', 10, 2)->default(0.00);
            $table->integer('cantidad_ventas')->default(0);
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

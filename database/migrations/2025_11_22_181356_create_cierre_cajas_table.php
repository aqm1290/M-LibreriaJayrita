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
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha'); // fecha del cierre (hoy)
            $table->decimal('total_efectivo', 10, 2);
            $table->decimal('total_qr', 10, 2);
            $table->decimal('total_ventas', 10, 2);
            $table->integer('cantidad_ventas');
            $table->decimal('inicio_caja', 10, 2)->default(0); // opcional: cuanto había al abrir
            $table->string('reporte_pdf')->nullable(); // PDF del cierre
            $table->text('observaciones')->nullable();
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

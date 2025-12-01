<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos_caja', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('fecha');
            $table->time('hora_apertura');
            $table->decimal('monto_apertura', 10, 2);

            $table->boolean('activo')->default(true);

            $table->time('hora_cierre')->nullable();
            $table->decimal('monto_fisico_cierre', 10, 2)->nullable();

            $table->decimal('total_ventas', 10, 2)->default(0);
            $table->decimal('total_efectivo', 10, 2)->default(0);
            $table->decimal('total_qr', 10, 2)->default(0);
            $table->decimal('diferencia', 10, 2)->default(0);

            $table->text('observaciones')->nullable();
            $table->string('reporte_pdf')->nullable();
            $table->integer('cantidad_ventas')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos_caja');
    }
};

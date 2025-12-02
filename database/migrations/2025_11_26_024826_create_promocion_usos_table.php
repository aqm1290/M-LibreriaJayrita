<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promocion_usos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promocion_id')
                  ->constrained('promociones')
                  ->onDelete('cascade');

            // Cambiado a cliente_id porque tú usas modelo Cliente y tabla clientes
            $table->foreignId('cliente_id')
                  ->nullable()
                  ->constrained('clientes')
                  ->onDelete('set null');

            $table->foreignId('venta_id')
                  ->nullable()
                  ->constrained('ventas')
                  ->onDelete('cascade');

            $table->string('ip_address')->nullable();
            $table->timestamp('usado_en')->useCurrent();

            // Evita que un cliente use más de una vez la misma promo (opcional, pero recomendado)
            $table->unique(['promocion_id', 'cliente_id']);

            $table->index(['promocion_id', 'cliente_id']);
            $table->index('usado_en');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promocion_usos');
    }
};
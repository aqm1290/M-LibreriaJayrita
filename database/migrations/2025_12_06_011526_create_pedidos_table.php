<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            
            // Solo el campo, sin FK (más compatible)
            $table->unsignedBigInteger('cliente_id')->nullable();

            $table->string('cliente_nombre');
            $table->string('cliente_telefono')->nullable();
            $table->string('cliente_email')->nullable();
            $table->text('notas')->nullable();

            $table->enum('estado', ['borrador', 'reservado', 'confirmado', 'entregado', 'cancelado'])
                  ->default('borrador');

            $table->decimal('total', 12, 2)->default(0);
            $table->timestamp('expira_en')->nullable();
            $table->timestamp('entregado_en')->nullable();

            $table->timestamps();

            // Índices para velocidad
            $table->index('cliente_id');
            $table->index('estado');
            $table->index('expira_en');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
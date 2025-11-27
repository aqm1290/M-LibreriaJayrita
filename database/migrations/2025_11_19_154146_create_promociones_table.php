<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique()->nullable();
            $table->string('descripcion')->nullable();


            // 4 tipos de promoción SOLO
            $table->enum('tipo', [
                'descuento_porcentaje',
                'descuento_monto',
                '2x1',
                'compra_lleva'
            ]);

            $table->decimal('valor_descuento', 10, 2)->nullable();

            // Para 2x1
            $table->foreignId('producto_2x1_id')->nullable()->constrained('productos')->onDelete('set null');

            // Para Compra X lleva Y
            $table->foreignId('producto_compra_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->foreignId('producto_regalo_id')->nullable()->constrained('productos')->onDelete('set null');

            // Aplicar a toda la tienda o categoría
            $table->boolean('aplica_todo')->default(true);
            $table->foreignId('categoria_id')->nullable()->constrained()->onDelete('set null');

            $table->dateTime('inicia_en');
            $table->dateTime('termina_en')->nullable();
            $table->boolean('activa')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promociones');
    }
};
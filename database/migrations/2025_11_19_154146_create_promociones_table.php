<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique()->nullable();
            $table->text('descripcion')->nullable();

            // TIPO DE PROMOCIÓN
            $table->enum('tipo', [
                'descuento_porcentaje',
                'descuento_monto',
                '2x1',
                'compra_lleva'
            ]);

            $table->decimal('valor_descuento', 10, 2)->nullable();

            // LÍMITE DE USOS POR CLIENTE
            $table->integer('limite_usos')->nullable(); // null = ilimitado

            // PRODUCTOS ESPECÍFICOS (JSON)
            $table->json('products_2x1')->nullable();
            $table->json('products_compra')->nullable();
            $table->json('products_regalo')->nullable();

            // ÁMBITO DE APLICACIÓN
            $table->boolean('aplica_todo')->default(true);
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            $table->foreignId('modelo_id')->nullable()->constrained('modelos')->onDelete('set null');
            $table->json('productos_seleccionados')->nullable(); // IDs manuales

            // FECHAS Y ESTADO
            $table->dateTime('inicia_en');
            $table->dateTime('termina_en')->nullable();
            $table->boolean('activa')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
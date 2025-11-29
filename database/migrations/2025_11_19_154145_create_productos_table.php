<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->decimal('costo_compra', 10, 2);
            $table->string('codigo', 50)->unique();
            $table->string('url_imagen')->nullable();
            $table->string('color')->nullable();
            $table->string('tipo')->nullable();
            $table->boolean('activo')->default(true);

            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('modelo_id')->constrained('modelos')->onDelete('cascade');
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');
            $table->foreignId('promo_id')->nullable();
            
            
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marca_id')
                ->constrained('marcas')
                ->onDelete('cascade');

            $table->foreignId('categoria_id')
                ->nullable() // al inicio, para no romper datos
                ->constrained('categorias')
                ->nullOnDelete();

            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};
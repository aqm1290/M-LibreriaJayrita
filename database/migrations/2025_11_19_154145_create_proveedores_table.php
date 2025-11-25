<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();

            // Datos principales
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->string('nit', 50)->nullable();
            $table->string('empresa', 150)->nullable();

            // Nuevos campos solicitados
            $table->string('contacto_nombre')->nullable();      // persona de contacto
            $table->string('contacto_telefono')->nullable();    // teléfono contacto
            $table->enum('estado', ['activo', 'inactivo'])->default('activo'); // estado

            $table->timestamps();

            // Índices
            $table->index('nombre', 'proveedores_nombre_index');
            $table->index('correo', 'proveedores_correo_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};

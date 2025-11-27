<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->decimal('total', 12, 2);
            $table->string('estado_pago')->default('pendiente');
            $table->string('metodo_pago')->nullable();
            $table->decimal('descuento_total', 10, 2)->default(0);
            

            
           $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

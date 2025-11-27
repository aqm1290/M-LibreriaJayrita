<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cierre_cajas', function (Blueprint $table) {
            // Cambiamos fecha a solo DATE (sin hora)
            $table->date('fecha')->change();

            $table->enum('estado', ['abierto', 'pausado', 'cerrado'])->default('abierto')->after('caja_abierta');
            $table->text('motivo_pausa')->nullable()->after('observaciones');   
            // Agregamos columna turno (solo si no existe)
            if (!Schema::hasColumn('cierre_cajas', 'turno')) {
                $table->unsignedTinyInteger('turno')->default(1)->after('fecha');
            }

            // Creamos el índice único (usuario + fecha + turno) → permite varios turnos por día
            $table->unique(['usuario_id', 'fecha', 'turno'], 'unique_turno_diario');
        });
    }

    public function down()
    {
        Schema::table('cierre_cajas', function (Blueprint $table) {
            $table->dropUnique('unique_turno_diario');
            if (Schema::hasColumn('cierre_cajas', 'turno')) {
                $table->dropColumn('turno');
            }
        });
    }
};
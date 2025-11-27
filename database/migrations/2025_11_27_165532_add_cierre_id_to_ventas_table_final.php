<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Solo agrega la columna si NO existe
            if (!Schema::hasColumn('ventas', 'cierre_id')) {
                $table->foreignId('cierre_id')
                      ->nullable()
                      ->after('usuario_id')
                      ->constrained('cierre_cajas')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (Schema::hasColumn('ventas', 'cierre_id')) {
                $table->dropForeign(['cierre_id']);
                $table->dropColumn('cierre_id');
            }
        });
    }
};
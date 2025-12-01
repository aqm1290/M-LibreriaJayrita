<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('turno_id')->nullable()->after('usuario_id');

            $table->foreign('turno_id')
                ->references('id')->on('turnos_caja')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['turno_id']);
            $table->dropColumn('turno_id');
        });
    }
};

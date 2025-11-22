<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cierres_caja', function (Blueprint $table) {
            $table->decimal('monto_apertura', 10, 2)->default(0)->after('usuario_id');
            $table->boolean('caja_abierta')->default(false);
        });
    }

    public function down()
    {
        Schema::table('cierres_caja', function (Blueprint $table) {
            $table->dropColumn(['monto_apertura', 'caja_abierta']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dateTime('fecha_venta')->nullable()->after('ticket_pdf');
            $table->unsignedBigInteger('cierre_id')->nullable()->after('fecha_venta');
            $table->foreign('cierre_id')->references('id')->on('cierre_cajas')->nullOnDelete();
            $table->index('fecha_venta');
            $table->index('cierre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            //
        });
    }
};

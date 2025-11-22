<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('ticket_pdf')->nullable()->after('descuento_total');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('ticket_pdf');
        });
    }
};
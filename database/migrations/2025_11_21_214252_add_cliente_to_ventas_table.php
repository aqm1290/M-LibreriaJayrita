<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('cliente_nombre')->nullable()->after('usuario_id');
            $table->string('cliente_documento')->nullable()->after('cliente_nombre'); // CI o NIT
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['cliente_nombre', 'cliente_documento']);
        });
    }
};
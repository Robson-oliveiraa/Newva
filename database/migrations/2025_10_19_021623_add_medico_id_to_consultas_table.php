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
        Schema::table('consultas', function (Blueprint $table) {
            $table->foreignId('medico_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['agendada', 'confirmada', 'realizada', 'cancelada'])->default('agendada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropForeign(['medico_id']);
            $table->dropColumn(['medico_id', 'status']);
        });
    }
};

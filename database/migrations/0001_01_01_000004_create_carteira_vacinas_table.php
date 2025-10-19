<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarteiraVacinaTable extends Migration
{
    public function up()
    {
        Schema::create('carteira_vacina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vacina_id')->constrained()->onDelete('cascade');
            $table->date('data_aplicacao');
            $table->date('vencimento')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carteira_vacina');
    }
}
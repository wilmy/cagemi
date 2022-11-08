<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_comunidades_x_grupo_empresarial', function (Blueprint $table) {
            $table->id('cod_comunidad');
            $table->integer('cod_grupo_empresarial');
            $table->string('nombre');
            $table->string('foto');
            $table->string('estatus', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_comunidades_x_grupo_empresarial');
    }
};

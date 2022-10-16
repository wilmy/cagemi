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
        Schema::create('tb_empresas_x_grupos_empresariales', function (Blueprint $table) {
            $table->id('cod_empresa');
            $table->integer('cod_grupo_empresarial');
            $table->string('nombre');
            $table->integer('cod_pais');
            $table->string('estatus', 1)->default('A');
            $table->string('logo');
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
        Schema::dropIfExists('tb_empresas_x_grupos_empresariales');
    }
};

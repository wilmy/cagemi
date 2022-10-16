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
        Schema::create('tb_empleados_x_departamentos', function (Blueprint $table) {
            $table->id('cod_empleado');
            $table->integer('cod_posicon');
            $table->integer('cod_departamento');
            $table->string('nombre_posicion');
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
        Schema::dropIfExists('tb_empleados_x_departamentos');
    }
};

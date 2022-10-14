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
        Schema::create('tb_carga_datos_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_grupo_empresarial');
            $table->integer('cod_empresa');
            $table->string('empresa');
            $table->integer('cod_empleado');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('posicion');
            $table->string('direccion_vp');
            $table->string('departamento');
            $table->string('elefono_movil');
            $table->string('extencion');
            $table->string('correo_instutucional');
            $table->string('correo_personal');
            $table->string('documento');

            $table->datetime('fecha_nacimiento');
            $table->string('codigo_superfisor');
            $table->string('estautus', 1)->default('A');
            $table->string('validacion_empresa', 1)->default('N');
            $table->string('validacion_VP', 1)->default('N');
            $table->string('validacion_departamento', 1)->default('N');
            $table->string('validacion_posicon', 1)->default('N');
            $table->string('validacion_empleados', 1)->default('N');
            $table->string('empresa_agregada', 1)->default('N');
            $table->string('vicepresidencia_agregada', 1)->default('N');
            $table->string('departamento_agregado', 1)->default('N');
            $table->string('posicion_agregada', 1)->default('N');
            $table->string('empleado_agregado', 1)->default('N');
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
        Schema::dropIfExists('tb_carga_datos_temp');
    }
};

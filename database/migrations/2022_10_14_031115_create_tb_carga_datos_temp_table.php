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
            $table->string('empresa')->nullable();
            $table->integer('cod_empleado')->nullable();
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('posicion')->nullable();
            $table->string('direccion_vp')->nullable();
            $table->string('departamento')->nullable();
            $table->string('telefono_movil')->nullable();
            $table->string('extencion')->nullable();
            $table->string('correo_instutucional')->nullable();
            $table->string('correo_personal')->nullable();
            $table->string('documento')->nullable();

            $table->datetime('fecha_nacimiento')->nullable();
            $table->string('codigo_superfisor')->nullable();
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
            $table->integer('fila')->nullable();
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

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
            $table->integer('cod_posicion');
            $table->integer('cod_supervisor');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('documento', 20);
            $table->string('telefono_movil', 20)->nullable();
            $table->string('telefono_institucional', 20)->nullable();
            $table->string('extencion', 8)->nullable();
            $table->string('correo_institucional')->nullable();
            $table->string('correo_personal')->nullable();
            $table->datetime('fecha_nacimiento')->nullable();
            $table->string('foto')->nullable();
            $table->string('estatus', 1);
            $table->datetime('activo_hasta')->nullable();
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

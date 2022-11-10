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
        Schema::create('tb_vicepresidencia_x_empresa', function (Blueprint $table) {
            $table->id('cod_vicepresidencia');
            $table->foreignId('cod_empresa');
            $table->string('nombre_vicepresidencia');
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
        Schema::dropIfExists('tb_vicepresidencia_x_empresa');
    }
};

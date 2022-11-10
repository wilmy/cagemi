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
        Schema::create('tb_visitas_x_publicaciones', function (Blueprint $table) {
            $table->id('cod_vista');
            $table->foreignId('cod_publicacion');
            $table->foreignId('cod_usuario');
            $table->datetime('fecha_vista');
            $table->datetime('tiempo_visualizacion');
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
        Schema::dropIfExists('tb_visitas_x_publicaciones');
    }
};

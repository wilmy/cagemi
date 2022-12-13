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
        Schema::create('tb_reaciones_x_publicaciones', function (Blueprint $table) {
            $table->id('cod_reaccion');
            $table->foreignId('cod_publicacion');
            $table->foreignId('cod_usuario');
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
        Schema::dropIfExists('tb_reaciones_x_publicaciones');
    }
};

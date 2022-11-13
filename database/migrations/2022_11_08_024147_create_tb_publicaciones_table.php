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
        Schema::create('tb_publicaciones', function (Blueprint $table) {
            $table->id('cod_publicacion');
            $table->foreignId('cod_comunidad');
            $table->foreignId('cod_tipo_publicacion');
            $table->string('texto', 2048); 
            $table->string('permite_comentario', 1);
            $table->string('permite_reaccion', 1);
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
        Schema::dropIfExists('tb_publicaciones');
    }
};

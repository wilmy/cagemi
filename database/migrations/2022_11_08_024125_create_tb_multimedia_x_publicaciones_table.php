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
        Schema::create('tb_multimedia_x_publicaciones', function (Blueprint $table) {
            $table->id('cod_elemento');
            $table->foreignId('cod_publicacion');
            $table->string('server');
            $table->string('nombre_archivo');
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
        Schema::dropIfExists('tb_multimedia_x_publicaciones');
    }
};

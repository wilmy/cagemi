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
        Schema::create('pantallas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_padre')->nullable();
            $table->string('nombre', 80);
            $table->string('slug', 100);  
            $table->string('url')->nullable();
            $table->string('descripcion', 250)->nullable();
            $table->integer('orden');
            $table->string('icono', 100)->nullable(); 
            $table->string('ver', 50)->nullable();
            $table->string('crear', 50)->nullable();
            $table->string('editar', 50)->nullable();
            $table->string('eliminar', 50)->nullable();
            $table->string('estatus', 1);
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
        Schema::dropIfExists('pantallas');
    }
};

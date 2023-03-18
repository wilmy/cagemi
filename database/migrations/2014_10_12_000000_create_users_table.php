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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('super_usuario', 1)->nullable();
            $table->foreignId('cod_empresa');  
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cambio_password', 1)->default('S');
            $table->rememberToken();
            $table->string('tipo_usuario', 1)->nullable();
            $table->string('token_dispositivo')->nullable();
            $table->string('token_autentication')->nullable();
            $table->string('token_app')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->timestamps();
            $table->foreignId('usuario_creacion')->nullable();  
            $table->foreignId('usuario_modificacion')->nullable();  
            $table->datetime('fecha_hora_inicio')->nullable(); 
            $table->datetime('fecha_hora_fin')->nullable();  
            $table->integer('confirmar_cuenta')->nullable();
            $table->string('estatus', 1)->nullable()->default('A'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

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
            $table->foreignId('cod_grupo_empresarial')->nullable();
            $table->foreignId('cod_empleado')->nullable();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cambio_password', 1)->default('S');
            $table->rememberToken();
            $table->string('tipo_usuario', 1)->nullable();
            $table->string('token_dispositivo')->nullable();
            $table->string('token_autentication')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
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

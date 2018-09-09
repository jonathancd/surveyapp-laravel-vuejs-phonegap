<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('program_id');

            $table->string('name');
            $table->string('email');
            $table->string('password');

            $table->string('ci');
            $table->string('codigo_estudiante');
            $table->date('fecha_nacimiento');
            $table->string('genero');
            $table->string('estrato');
            $table->string('programa');
            $table->string('semestre');
            $table->string('telefono');
            $table->string('rol')->default(1);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

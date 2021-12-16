<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_imagen', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idempleado')->unsigned();
            $table->string('name', 50)->nullable();
            $table->string('myname', 50)->nullable();
            $table->string('mimetype', 20)->nullable();
            $table->foreign('idempleado')->references('id')->on('empleado');
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
        Schema::dropIfExists('empleado_imagen');
    }
}

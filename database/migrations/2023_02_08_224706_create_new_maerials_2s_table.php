<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewMaerials2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_maerials_2s', function (Blueprint $table) {
            $table->id();
            $table->integer('lotid')->unsigned();
            $table->string('Product');
            $table->string('Thickness');
            $table->string('Width');
            $table->string('Length');
            $table->string('Weight');
            $table->string('Grade');
            $table->string('Remark');
            $table->string('images');
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
        Schema::dropIfExists('new_maerials_2s');
    }
}

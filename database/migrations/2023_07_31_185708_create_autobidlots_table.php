<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobidlotsTable extends Migration
{
    public function up()
    {
        Schema::create('autobidlots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customerId');
            $table->unsignedBigInteger('lotId');
            $table->integer('amount'); // Add the amount column
            $table->boolean('autobid')->default(false);
            $table->timestamps();

            $table->foreign('customerId')->references('id')->on('customers');
            $table->foreign('lotId')->references('id')->on('lots');
        });
    }

    public function down()
    {
        Schema::dropIfExists('autobidlots');
    }
}



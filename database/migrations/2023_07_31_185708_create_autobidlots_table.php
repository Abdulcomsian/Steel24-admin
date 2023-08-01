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
            $table->boolean('autobid')->default(0); // Changed the default value to 0 (disabled) for clarity
            $table->timestamps();

            $table->foreign('customerId')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('lotId')->references('id')->on('lots')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('autobidlots');
    }
}



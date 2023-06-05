<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('lotid')->unsigned();
            $table->string('Payment_Terms');
            $table->string('Price_Bases');
            $table->string('Texes_and_Duties');
            $table->string('Commercial_Terms');
            $table->string('Test_Certificate');

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
        Schema::dropIfExists('lot_terms');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsOfLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids_of_lots', function (Blueprint $table) 
        {
            // $table->id();
            // $table->integer('customerId');
            // $table->integer('amount');
            // $table->integer('lotId');
            // $table->timestamps();
            $table->id();
            $table->unsignedBigInteger('customerId');
            $table->integer('amount');
            $table->unsignedBigInteger('lotId');
            $table->boolean('autoBid')->default(0);
            $table->timestamps();

            $table->foreign('customerId')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('lotId')->references('id')->on('lots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bids_of_lots');
    }
}

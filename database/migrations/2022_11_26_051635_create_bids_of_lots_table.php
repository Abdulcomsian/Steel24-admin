<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsOfLotsTable extends Migration
{
    public function up()
    {
        Schema::create('bids_of_lots', function (Blueprint $table) 
        {
            $table->id();
            $table->unsignedBigInteger('customerId'); // Use unsignedBigInteger for foreign keys
            $table->integer('amount');
            $table->unsignedBigInteger('lotId'); // Use unsignedBigInteger for foreign keys
            $table->boolean('autoBid')->default(0);
            $table->timestamps();

            $table->foreign('customerId')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('lotId')->references('id')->on('lots')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bids_of_lots');
    }
}

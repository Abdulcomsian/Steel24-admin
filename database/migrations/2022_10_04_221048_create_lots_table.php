<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->integer('categoryId')->nullable();
            $table->integer('uid');
            $table->string('Seller')->nullable();
            $table->string('Plant')->nullable();
            $table->string('materialLocation')->nullable();
            $table->string('Quantity')->nullable();
            $table->dateTime('StartDate');
            $table->dateTime('EndDate');
            $table->string('Price')->nullable();
            // $table->string('Material')->nullable();
            $table->string('lot_status')->nullable();
            $table->boolean('auction_status')->default(false);
            $table->string('customFields')->default('');
            $table->timestamps();
        });
    }

    /**Location
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lots');
    }
}

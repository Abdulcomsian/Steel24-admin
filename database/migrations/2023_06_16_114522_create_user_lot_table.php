<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLotTable extends Migration
{
    public function up()
    {
        Schema::create('user_lot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lot_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('cascade');

            $table->unique(['user_id', 'lot_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_lot');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("customer_id");
            $table->unsignedBigInteger("lot_id");
            $table->foreign("customer_id")->references("id")->on("customers")->onDelete('cascade');
            $table->foreign("lot_id")->references("id")->on("lots")->onDelete('cascade');
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
        Schema::dropIfExists('customer_lots');
    }
}

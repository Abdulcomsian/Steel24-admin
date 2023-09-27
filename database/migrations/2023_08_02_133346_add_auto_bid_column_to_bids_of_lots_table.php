<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoBidColumnToBidsOfLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids_of_lots', function (Blueprint $table) {
            $table->boolean('autoBids')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bids_of_lots', function (Blueprint $table) {
            $table->dropColumn('autoBid');
        });
    }
}

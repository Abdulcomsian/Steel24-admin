<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\lots;

class update_lot extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // update lots
        // set lot_status = 'active';

        lots::where('lot_status' , 'active')->update(['lot_status' => 'live']);
    }
}

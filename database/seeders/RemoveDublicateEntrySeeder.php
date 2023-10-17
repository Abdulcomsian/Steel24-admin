<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BidsOfLots;

class RemoveDublicateEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repeatedBids = BidsOfLots::select('id' , 'lotId' , 'amount')->groupBy('lotId' , 'amount')->get()->pluck('id')->toArray();
        BidsOfLots::whereNotIn('id' , $repeatedBids)->delete();
    }
}

// Route::get('*' , function)
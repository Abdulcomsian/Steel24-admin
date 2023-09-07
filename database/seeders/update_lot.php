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

        $customer = \App\Models\Customer::where('email','jabirchaudhary660@gmail.com')->update(['password' => \Illuminate\Support\Facades\Hash::make('123456')]);
        dd($customer);
        // ->update(['password' => \Illuminate\Support\Facades\Hash::make('123456')]);
        // lots::where('lot_status' , 'active')->update(['lot_status' => 'live']);
    }
}

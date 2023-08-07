<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\lotTerms;
use App\Models\categories;
use App\Models\Customer;
use App\Models\new_maerials_2;
use App\Models\customerBalance;
use Illuminate\Support\Facades\DB;

class lots extends Model
{
    use HasFactory;
    protected $table = 'lots';
    protected $primaryKey = 'id';
    protected  $fillable =  [
        'title', 'description', 'categoryId', 'uid', 'Seller', 'Plant', 'materialLocation', 'Quantity','Payment_terms',
        'StartDate', 'EndDate', 'Price', 'auction_status', 'lot_status', 'customFields', 'participate_fee', 'ReStartDate', 'ReEndDate', 'LiveSequenceNumber','status'
    ];

    public function materials()
    {
        // return $this->belongsToMany(materials::class, 'lot_materials');
    }

    public function categories()
    {
        return $this->belongsTo(categories::class, 'categoryId', 'id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'user_lot', 'lot_id', 'customer_id')
            ->withTimestamps();
    }

    public function lotTerms()
    {
        return $this->hasOne(lotTerms::class, 'lotid');
    }

      public function new_maerials_2()
    {
        return $this->hasMany(new_maerials_2::class, 'lotid');
    }

    public function customerBalance()
    {
        return $this->hasMany(customerBalance::class , 'lotid' , 'id');
    }

    public function bids()
    {
        return $this->hasMany(BidsOfLots::class, 'lotId', 'id');
    }

    public function maxBid()
    {
        return DB::table('bids_of_lots')
            ->select('customerId', 'amount', 'lotId', 'created_at')
            ->where('lotId', $this->id)
            ->orderBy('amount', 'DESC')
            ->first();
    }

    public function autoBids()
    {
        return $this->hasMany(AutoBid::class, 'lotId');
    }
}

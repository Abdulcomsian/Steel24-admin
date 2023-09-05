<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected $dates = ['EndDate'];

    protected  $fillable =  [
        'title', 
        'description', 
        'categoryId',
        'uid',
        'Seller',
        'Plant',
        'materialLocation', 
        'Quantity',
        'Payment_terms',
        'StartDate',
        'EndDate',
        'Price',
        'auction_status',
        'lot_status',
        'customFields',
        'participate_fee',
        'ReStartDate',
        'ReEndDate',
        'LiveSequenceNumber',
        'status'
    ];

   
    // Convert the EndDate attribute to a Carbon instance with the correct format
    public function getEndDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value);
    }

        public function materialss()
    {
        return $this->belongsToMany(new_maerials_2::class, 'new_maerials_2s', 'id', 'lotId');
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
        return $this->hasMany(new_maerials_2::class, 'lotid','id');
    }

    public function lot()
    {
        return $this->hasMany(lots::class ,'lot_id','id');
    }

    public function customerBalance()
    {
        return $this->hasMany(customerBalance::class , 'lotid', 'id');
    }

    public function bids()
    {
        return $this->hasMany(BidsOfLots::class, 'lotId', 'id');
    }

    public function maxBid()
    {
        return DB::table('bids_of_lots')
            ->select('id','customerId', 'amount', 'lotId', 'created_at','updated_at')
            ->where('lotId', $this->id)
            ->orderBy('amount', 'DESC')
            ->first();
    }

    public function autoBids()
    {
        return $this->hasMany(AutoBid::class, 'lotId');
    }

    public function participant()
    {
        return $this->belongsToMany(Customer::class , 'lot_participants' , 'lot_id' , 'customer_id')->withPivot('status');
    }

    public function engageLot()
    {
        return $this->belongsToMany(lots::class , 'lot_participants' , 'customer_id' , 'lot_id');
    }

    public function materials()
    {
        return $this->hasMany(new_maerials_2::class, 'lotid');
    }

    public function lotDetails()
    {
        return $this->hasOne(CustomerLot::class, 'lot_id');
    }

}

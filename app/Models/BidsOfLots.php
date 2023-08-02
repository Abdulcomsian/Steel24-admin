<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidsOfLots extends Model
{
    use HasFactory;
    protected $fillable = ['customerId', 'amount', 'lotId'];


    // , 'autoBid', 'created_at', 'updated_at'

    public function lotDetails()
    {
        return $this->belongsTo(lots::class, 'lotId');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

}

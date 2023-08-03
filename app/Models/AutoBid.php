<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoBid extends Model
{
    use HasFactory;

    protected $table = 'autobidlots';
    protected $fillable = ['customerId', 'lotId', 'autobid'];

    // Relationship with Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

    // Relationship with Lot model
    public function lots()
    {
        return $this->belongsTo(lots::class, 'lotId');
    }

    // // Relationship with Lot model
    // public function lot()
    // {
    //     return $this->belongsTo(lots::class, 'lotId');
    // }
}



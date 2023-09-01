<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerBalance extends Model
{
    use HasFactory;

    protected $fillable = ['customerId', 'balanceAmount', 'action', 'actionAmount', 'finalAmount', 'date', 'lotid','status'];

    public function customerDetails()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

    public function lot()
    {
        return $this->belongsTo(lots::class, 'lot_id', 'id');
    }

    public function lots()
    {
        return $this->belongsTo(lots::class, 'lotid');
    }


}

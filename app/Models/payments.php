<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;
    protected $table = 'payments';

    protected $fillable = [
        'lotId',
        'customerId',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        "Date", "customerVisible"
    ];


    public function lotDetails()
    {
        return $this->belongsTo(lots::class, 'lotId');
    }


    public function customerDetails()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

}

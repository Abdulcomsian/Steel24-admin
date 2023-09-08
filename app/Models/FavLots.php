<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavLots extends Model
{
    use HasFactory;

    protected $table = 'user_lot';

    protected  $fillable =[
        'customer_id',
        'lot_id',
    ];


    public function bids()
    {
        return $this->hasMany(BidsOfLots::class, 'customerId');
    }

    public function lot()
    {
        return $this->belongsTo(lots::class , 'lot_id' ,'id');
    }

    public function favlots()
    {
        return $this->belongsTo(lots::class , 'lot_id' ,'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidsOfLots extends Model
{
    use HasFactory;
    public $fillable =  ["customerId", "amount", "lotId"];


    public function lotDetails()
    {
        return $this->belongsTo(lots::class, 'lotId');
    }
}

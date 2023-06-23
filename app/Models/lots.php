<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\lotTerms;

class lots extends Model
{
    use HasFactory;
    protected $table = 'lots';
    protected  $fillable =  [
        'title', 'description', 'categoryId', 'uid', 'Seller', 'Plant', 'materialLocation', 'Quantity','Payment_terms',
        'StartDate', 'EndDate', 'Price', 'auction_status', 'lot_status', 'customFields', 'participate_fee', 'ReStartDate', 'ReEndDate', 'LiveSequenceNumber'
    ];

    public function materials()
    {
        // return $this->belongsToMany(materials::class, 'lot_materials');
    }

    public function categories()
    {
        return $this->belongsTo(categories::class, 'categoryId', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_lot')->withTimestamps();
    }

    public function lotTerms()
    {
        return $this->hasOne(lotTerms::class, 'lotid');
    }

      public function new_maerials_2()
    {
        return $this->hasMany(new_maerials_2::class, 'lotid');
    }
}

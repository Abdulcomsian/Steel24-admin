<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lots extends Model
{
    use HasFactory;
    protected $table = 'lots';
    protected  $fillable =    [
        'title', 'description', 'categoryId', 'uid', 'Seller', 'Plant', 'materialLocation', 'Quantity',
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
}

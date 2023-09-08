<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Customer , lots};

class CustomerLot extends Model
{
    use HasFactory;

    protected $table = "customer_lots";

    protected $primaryKey = "id";

    protected $fillable = ["customer_id" , "lot_id"];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id' , 'id');
    }

    // public function lot()
    // {
    //     return $this->hasMany(lots::class ,'id', 'lot_id' );
    // }


    public function lot()
    {
        return $this->hasMany(lots::class ,'id', 'lot_id' );
    }

    public function lotDetail()
    {
        return $this->belongsTo(lots::class ,'lot_id' ,'id');
    }

    public function new_maerials_2()
    {
        return $this->hasMany(new_maerials_2::class, 'id', 'lot_id');
    }

    public function categories()
    {
        return $this->belongsTo(categories::class, 'categoryId', 'id');
    }

}

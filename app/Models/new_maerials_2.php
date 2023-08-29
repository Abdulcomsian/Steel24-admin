<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  new_maerials_2 extends Model
{
    use HasFactory;

    protected $fillable = [
        "lotid",
        "Product",
        "Thickness",
        "Width",
        "Length",
        "Weight",
        "Grade",
        "Remark",
        "images",
    ];

    public function lots()
    {
        return $this->belongsToMany(lots::class, 'new_maerials_2s', 'lotId', 'id'); 
    }

    
}

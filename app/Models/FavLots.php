<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavLots extends Model
{
    use HasFactory;
    protected  $fillable =[
        'user_id ',
        'lot_id ',
    ];
}

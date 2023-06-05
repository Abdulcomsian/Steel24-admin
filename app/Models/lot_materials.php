<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lot_materials extends Model
{
    protected $table = 'lot_materials';

    use HasFactory;
    protected $fillable = ['lots_id', 'materials_id',];
}

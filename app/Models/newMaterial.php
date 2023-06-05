<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['lotid', 'materialid', 'materialdata','image'];

}

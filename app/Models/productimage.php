<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productimage extends Model
{

    use HasFactory;
    protected $fillable = ['title', 'description', 'image'];

    // public function lots()
    // {
    //     return $this->belongsToMany(lots::class, 'categoryId', 'id');
    // }
}

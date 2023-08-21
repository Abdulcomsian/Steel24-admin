<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'parentcategory'];

    public function lots()
    {
        return $this->belongsToMany(lots::class, 'categoryId', 'id');
    }

    public function lot()
    {
        return $this->hasMany(lots::class, 'categoryId');
    }
    

}

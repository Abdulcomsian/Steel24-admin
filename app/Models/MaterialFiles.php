<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'images_names','material_id'
    ];

    public function setFilenamesAttribute($value)
    {
        $this->attributes['images_names'] = json_encode($value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelCategoryOfLots extends Model
{
    use HasFactory;

    protected $fillable = ['url'];
    protected $table = 'excelcategoryoflots';
}

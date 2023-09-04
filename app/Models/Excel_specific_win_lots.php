<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excel_specific_win_lots extends Model
{
    use HasFactory;

    protected $fillable = ['url'];
    protected $table = 'specific_lots';
}

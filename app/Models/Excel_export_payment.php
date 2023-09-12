<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excel_export_payment extends Model
{
    use HasFactory;

    protected $fillable = ['url'];
    protected $table = 'Paymentexcelexport';
}

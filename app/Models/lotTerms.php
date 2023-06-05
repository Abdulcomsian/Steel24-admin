<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lotTerms extends Model
{
    use HasFactory;
    protected  $fillable =    [
        'lotid',
        'Payment_Terms',
        'Price_Bases',
        'Texes_and_Duties',
        'Commercial_Terms',
        'Test_Certificate',
    ];
}

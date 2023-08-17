<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotParticipant extends Model
{
    use HasFactory;

    protected $table = "lot_participants"; 
    
    protected $primaryKey = "id";

    protected $fillable = ["customer_id" , "lot_id" ,"status"];

}

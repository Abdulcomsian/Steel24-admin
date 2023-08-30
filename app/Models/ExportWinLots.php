<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportWinLots extends Model
{
    use HasFactory;

    protected $fillable = ['url'];
    protected $table = 'exported_files_win_lots';
}

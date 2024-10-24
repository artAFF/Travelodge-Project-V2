<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uncm_server extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_temp',
        'ups_temp',
        'ups_battery',
        'ups_time'
    ];
}

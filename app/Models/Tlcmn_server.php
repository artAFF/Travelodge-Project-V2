<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tlcmn_server extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_temp',
        'ups_temp',
        'ups_battery',
        'ups_time',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
